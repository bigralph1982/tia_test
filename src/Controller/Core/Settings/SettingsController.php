<?php

namespace App\Controller\Core\Settings;

use App\Entity\Core\Settings\Settings;
use App\Form\Core\Settings\SettingsType;
use App\Service\Core\General\MailerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\ORM\EntityManagerInterface;

/**
 * SEOTags controller.
 *
 * @Route("/backauth/settings") 
 */
class SettingsController extends AbstractController
{

    private $em;
    private $mailer;
    
    public function __construct(EntityManagerInterface $em, MailerService $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    /**
     * Displays a form to edit an existing Settings entity.
     *
     * @Route("/test-mailer", name="admin_settings_test_mailer")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_DEVELOPER')")
     */
    public function testMailer(Request $request) {

        $to = $request->query->get("to");
        if($to){
            $row = [
                "subject" => "Admin Test Message", 
                'to' => $to, 
                'content' => "This is a test message", 
                'cc' => "fouad@sync.com.lb", 
            ];
            $res = $this->mailer->sendMessage($row);
           
            $this->addFlash("success", "Email sent successfully!");
        }
        
        return $this->redirectToRoute("admin_settings_edit");

    }


    /**
     * Creates a new page entity.
     *
     * @Route("/new", name="admin_settings_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_DEVELOPER')")
     */
    public function editAction(Request $request): Response
    {

        $entity = $this->em->getRepository(Settings::class)->findAll();

        if (!$entity) {
            $entity = new Settings();
        } else {
            $entity = $entity[0];
        }


        $editForm = $this->createForm(SettingsType::class, $entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            if ($entity->getFile()) {
                $entity->setTstamp($entity->getStamp());
            }

            $this->em->persist($entity);
            $this->em->flush();
            $this->addFlash(
                    'success', 'products.savesuccess'
            );
            return $this->redirectToRoute('admin_settings_edit');
        }

        return $this->render('core/settings/form.html.twig', array(
                    'entity' => $entity,
                    'form' => $editForm->createView(),
        ));

       
    }

}
