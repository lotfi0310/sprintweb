<?php

namespace App\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request ;

class BackofficeController extends AbstractController
{
    /**
     * @Route("/choixTypeRec",name="choixtypeRec")
     */
    public function choixtyperec(){

        return $this->render('reclamation/choixtyperec.html.twig') ;

    }
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
