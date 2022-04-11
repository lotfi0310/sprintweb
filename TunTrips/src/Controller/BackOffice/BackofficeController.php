<?php

namespace App\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackofficeController extends AbstractController
{
    /**
     * @Route("/backhome", name="app_backoffice")
     */
    public function index(): Response
    {
        return $this->render('back_office/default/index.html.twig', [
            'controller_name' => 'BackofficeController',
        ]);
    }
}
