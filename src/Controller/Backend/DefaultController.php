<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/backauth/", name="backend_default")
     */
    public function index(): Response
    {
        return $this->render('backend/_default/index.html.twig', []);
    }
}
