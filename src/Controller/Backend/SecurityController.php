<?php

namespace App\Controller\Backend;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class SecurityController extends AbstractController
{
    /**
     * @Route("/backauth/login", name="administrators_login", methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        if($this->isGranted("ROLE_DEVELOPER")){
            return $this->redirectToRoute("administrators_index");
        }

        if($this->isGranted("ROLE_ADMIN")){
            return $this->redirectToRoute("backend_default");
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('backend/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/backauth/logout", name="administrators_logout", methods={"GET"})
     */
    public function logout(): Response
    {
        // controller can be blank: it will never be executed!
        //throw new \Exception('Don\'t forget to activate logout in security.yaml');

        return $this->redirectToRoute("administrators_login");
    }
}
