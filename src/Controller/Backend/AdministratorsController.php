<?php

namespace App\Controller\Backend;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Knp\Component\Pager\PaginatorInterface;

#
use App\Repository\Backend\AdministratorsRepository;
use App\Entity\Backend\Administrators;
use App\Form\Backend\AdministratorsType;
use App\Service\Core\General\MailerService;
use App\Service\Core\Settings\SettingsService;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;

/**
 * @Route("/backauth/administrators")
 */
class AdministratorsController extends AbstractController
{

    private $passwordEncoder;
    private $em;
    private $mailer;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em, MailerService $mailer, SettingsService $settingsService)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
        $this->mailer = $mailer;
    }
 
 
    /**
     * @Route("/", name="administrators_index", methods={"GET"})
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function index(Request $request, PaginatorInterface $paginator, AdministratorsRepository $administratorsRepository): Response
    {

        $user = $this->getUser();
        $where = "";
        if ($user->getRoleId() !== 999) {

            $where = " and p.role_id  != 999 ";
        }

        $query = $administratorsRepository->createQueryBuilder('p')
            ->where("p.isActive in (1,2) {$where}")
            ->orderBy("p.id", "desc")
            ->getQuery();


        $entities =  $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('backend/administrators/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * @Route("/new", name="administrators_new", methods={"GET","POST"})
     * Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function new(Request $request): Response
    {
        $entity = new Administrators();

        $entity->setStatus(1);

        $validator = new EmailValidator();
        $multipleValidations = new MultipleValidationWithAnd([
            new RFCValidation(),
            new DNSCheckValidation()
        ]);

        
        $form = $this->createForm(AdministratorsType::class, $entity, ['validation_groups' => ['administratorCreate']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($timezone_name = $entity->getTimezone()){
                $request->getSession()->set('_timezone', $timezone_name);
            }

            $value = $validator->isValid($entity->getEmail() , $multipleValidations);
            if( $value == false ){
                $this->addFlash("success", "The email isn't correct");
                return $this->render('backend/administrators/form.html.twig', [
                    'entity' => $entity,
                    'form' => $form->createView(),
                ]);
            }

            if (!empty($entity->text_password)) {

                $entity->setPassword(
                    $this->passwordEncoder->encodePassword(
                        $entity,
                        $entity->text_password
                    )
                );

                $entity->setRoles([$entity->roles_array[$entity->getRoleId()]]);
                
                $this->em->persist($entity);
                $this->em->flush();

                $this->addFlash("success", "The entry has been created");
            }

            return $this->redirectToRoute('administrators_index');
        }

        return $this->render('backend/administrators/form.html.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="administrators_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function edit(Request $request, Administrators $entity): Response
    {

        if (!in_array($entity->getIsActive(), array(1, 2))) {
            return $this->redirectToRoute('administrators_index');
        }

        $validator = new EmailValidator();
        $multipleValidations = new MultipleValidationWithAnd([
            new RFCValidation(),
            new DNSCheckValidation()
        ]);

        $this->validateRole($entity);
        $form = $this->createForm(AdministratorsType::class, $entity, ['validation_groups' => ['administratorEdit'], 'role'=>$entity->getRoleId(), 'user' => $this->getUser() ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($timezone_name = $entity->getTimezone()){
                $request->getSession()->set('_timezone', $timezone_name);
            }

            $value = $validator->isValid($entity->getEmail() , $multipleValidations);
            if( $value == false ){
                $this->addFlash("success", "The email isn't correct");
                return $this->redirectToRoute('administrators_edit' , array('id'=>$entity->getId()));
            }

            if (!empty($entity->text_password)) {

                $entity->setPassword(
                    $this->passwordEncoder->encodePassword(
                        $entity,
                        $entity->text_password
                    )
                );
            }

            $entity->setRoles([$entity->roles_array[$entity->getRoleId()]]);

            $this->em->flush();

            $this->addFlash("success", "The entry has been saved");

            return $this->redirectToRoute('administrators_index');
        }


        return $this->render('backend/administrators/form.html.twig', [
            'administrator' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="administrators_delete", methods={"GET"})
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function delete(Administrators $entity): Response
    {
        if (!in_array($entity->getIsActive(), array(1, 2))) {
            return $this->redirectToRoute('administrators_index');
        }
        $this->validateRole($entity);

        $entity->setIsActive(3);
        $this->em->flush();

        $this->addFlash("success", "The entry has been deleted!");

        return $this->redirectToRoute('administrators_index');
    }

    private function validateRole($entity)
    {

        if ($entity->getRoleId() == 999) {
            if ($this->getUser()->getRoleId() !== $entity->getRoleId()) {
                $this->addFlash("danger", "403");
                return $this->redirectToRoute('backend_default');
            }
        }
    }
}
