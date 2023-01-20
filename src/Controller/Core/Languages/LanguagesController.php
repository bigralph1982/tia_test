<?php

namespace App\Controller\Core\Languages;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

use App\Entity\Core\Languages\Languages;
use App\Service\Core\Languages\LanguagesService;
use App\Form\Core\Languages\LanguagesType;
use App\Repository\Core\Languages\LanguagesRepository;

/**
 * Languages controller.
 *
 * @Route("/backauth/languages")
 */
class LanguagesController extends AbstractController
{
    private $em;
    private $languagesService;

    public function __construct(EntityManagerInterface $em, LanguagesService  $languagesService)
    {
        $this->em = $em;
        $this->languagesService = $languagesService;
    }

    /**
     * Lists all Languages entities.
     *
     * @Route("/", name="admin_languages_index") 
     * @Method("GET")
     * @Security("is_granted('ROLE_DEVELOPER')")
     */
    public function indexAction(
        Request $request,
        PaginatorInterface $paginator,
        LanguagesRepository $entityRepository
    ): Response {

        $query = $entityRepository->createQueryBuilder('p')

            ->orderBy("p.title", "asc")
            ->getQuery();


        $entities =  $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            1
        );

        return $this->render('core/languages/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Languages entity.
     *
     * @Route("/new", name="admin_languages_new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_DEVELOPER')")
     */
    public function newAction(Request $request): Response
    {
        $entity = new Languages;

        $form = $this->createForm(LanguagesType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($entity->getIsMain() == true) {
                $this->languagesService->removeMain();
            }

            $this->em->persist($entity);
            $this->em->flush();

            $this->addFlash(
                'success',
                'products.savesuccess'
            );
            return $this->redirectToRoute('admin_languages_index');
        }

        return $this->render('core/languages/form.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Languages entity.
     *
     * @Route("/{id}/edit", name="admin_languages_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_DEVELOPER')")
     */
    public function editAction(Request $request, Languages $entity): Response
    {

        $editForm = $this->createForm(LanguagesType::class, $entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            if ($entity->getIsMain() == true) {
                $this->languagesService->removeMain();
            }


            $this->em->flush();
            $this->addFlash(
                'success',
                'products.savesuccess'
            );
            return $this->redirectToRoute('admin_languages_index');
        }


        return $this->render('core/languages/form.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        ));
    }

    /**
     *
     * @Route("/delete/{id}", name="admin_languages_delete")
     * @Method("GET")
     * @Security("is_granted('ROLE_DEVELOPER')")
     */
    public function deleteAction(Languages $entity): Response
    {

        $this->em->remove($entity);
        $this->em->flush();
        $this->addFlash("success", "The entry has been deleted!");

        return $this->redirectToRoute('admin_languages_index');
    }
}
