<?php

namespace App\Controller\Backend;

use App\Entity\Backend\Administrators;
use App\Entity\Backend\AdministratorsDevices;
use App\Form\Backend\APIAdministratorsLoginType;
use App\Service\Core\General\GlobalService;
use App\Service\Core\General\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/administrators")
 */
class APIAdministratorsController extends AbstractController
{

    protected $encoder;
    protected $em;
    protected $globalService;
    protected $mailerService;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em, GlobalService $globalService, MailerService $mailerService)
    {
        $this->encoder = $encoder;
        $this->em = $em;
        $this->globalService = $globalService;
        $this->mailerService = $mailerService;
    }


     /**
     * @Route("/login", name="api_administrators_login", methods={"POST"})
     */
    public function login(Request $request): Response
    {

        $error = true;
        $apiKey = false;
        $errors_data = $entity_data = [];

        $deviceUUID = $request->get("deviceUUID");
        $deviceModel = $request->get("deviceModel");
        
       

        if ($deviceUUID) {

            $entity = new Administrators();
            $class = Administrators::class;
            $devicesClass = AdministratorsDevices::class;


            

            $form = $this->createForm(APIAdministratorsLoginType::class, $entity);


            $data = json_decode($request->getContent(), true);

            if (!$data) {
                $data = [];
                parse_str($request->getContent(), $data);
            }

            $form->submit($data);

            if ($form->isSubmitted() && $form->isValid()) {
                $formdata = $form->getData();
                $username = $formdata->api_username;
                $password = $formdata->text_password;

                $logged_entity = $this->em->getRepository($class)->findOneBy(array('username' => $username, 'isActive' => [1,2]));

                if ($logged_entity) {


                    if ($this->encoder->isPasswordValid($logged_entity, $password)) {

                        
                        if($logged_entity->isEnabled()){
                                $deviceEntity = $this->em->getRepository($devicesClass)->findOneBy(["administrator" => $logged_entity->getId(), "deviceUUID" => $deviceUUID]);

                                if (!$deviceEntity) {
                                    $apiKey = $this->generateAPIKey($logged_entity->getId());
                                    $deviceEntity = new $devicesClass();
                                    $deviceEntity->setDeviceUUID($deviceUUID);
                                    $deviceEntity->setDeviceModel($deviceModel);
                                    $deviceEntity->setAdministrator($logged_entity);
                                    $deviceEntity->setApiKey($apiKey);
                                } else {
                                    $apiKey = $deviceEntity->getApiKey();

                                }
                                $deviceEntity->setLastLogin();
                                $this->em->persist($deviceEntity);
                                $this->em->flush();
                                $error = false;
        
        
                                $entity_data = $logged_entity->__serialize();
                            
                        }else{
                            $errors_data[] = "error.account_disabled";
                        }

                        
                    } else {
                        $errors_data[] = "error.invalid_password";
                    }
                } else {
                    $errors_data[] = "error.invalid_user_account";
                }
            } else {
                if (!$form->isSubmitted())
                    $errors_data[] = "Form is not submitted";

                if (!$form->isValid()) {
                    $errors = $this->globalService->serializeFormErrors($form, true, false);

                    $errors_data = $errors;
                }
            }
        } else {
            $errors_data[] = "Device UUID or type is not submitted";
        }


        $response = array(
            'error' => $error,
            'errors_data' => $errors_data,
            'apiKey' => $apiKey,
            'data' => $entity_data,
        );
        $return = json_encode([
            'data' => $response
        ]);



        return new Response($return, 200, array('Content-Type' => 'application/json', 'Access-Control-Allow-Origin' => '*', 'Access-Control-Allow-Headers' => ' Content-Type'));
    }

    public function generateAPIKey($id) {

        return md5($id . "-" . uniqid());
    }

}
