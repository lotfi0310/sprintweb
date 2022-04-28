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



}
