<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{

    /**
     * @Route("/map",name="street")
     */
    public function mapAction(): Response
    {
        return $this->render('map/index.html.twig');
    }



}
