<?php

namespace App\Controller;

use App\Entity\Revtransport;
use App\Entity\Transport;
use App\Form\TransportType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class TransportController extends AbstractController
{
    /**
     * @Route("/", name="display_Transport")
     */
    public function index(): Response
    {

        $Transports = $this->getDoctrine()->getManager()->getRepository(Transport::class)->findAll();
        return $this->render('Transport/index.html.twig', [
            'b'=>$Transports
        ]);
    }

    /**
     * @Route("/mylist", name="mylist")
     */
    public function mylist(): Response
    {

        $Transports = $this->getDoctrine()->getManager()->getRepository(Transport::class)->findBy([
            "id" => 2331 //idUser

        ]);
        return $this->render('Transport/indexFront.html.twig', [
            'b'=>$Transports
        ]);
    }


    /**
     * @Route("/front", name="display_front")
     */
    public function indexAdmin(): Response
    {        $Transports = $this->getDoctrine()->getManager()->getRepository(Transport::class)->findAll();


        return $this->render('transport/indexFront.html.twig'
            , [
                'b'=>$Transports
            ]);
    }


    /**
     * @Route("/addTransport", name="addTransport")
     */
    public function addTransport(Request $request): Response
    {
        $Transport = new Transport();

        $form = $this->createForm(TransportType::class,$Transport);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Transport);//Add
            $em->flush();

            return $this->redirectToRoute('display_Transport');
        }
        return $this->render('Transport/createTransport.html.twig',['f'=>$form->createView()]);

    }


    /**
     * @Route("/addFront", name="addFront")
     */
    public function addFrontTransport(Request $request): Response
    {
        $Transport = new Transport();

        $form = $this->createForm(TransportType::class,$Transport);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Transport);//Add
            $em->flush();

            return $this->redirectToRoute('display_Front');
        }
        return $this->render('Transport/createFrontTransport.html.twig',['f'=>$form->createView()]);




    }



    /**
     * @Route("/removeTransport/{id}", name="supp_Transport")
     */
    public function suppressionTransport(Transport  $Transport): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($Transport);
        $em->flush();

        return $this->redirectToRoute('display_Transport');


    }
    /**
     * @Route("/modifTransport/{id}", name="modifTransport")
     */
    public function modifTransport(Request $request,$id): Response
    {
        $Transport = $this->getDoctrine()->getManager()->getRepository(Transport::class)->find($id);

        $form = $this->createForm(TransportType::class,$Transport);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('display_Transport');
        }
        return $this->render('Transport/updateTransport.html.twig',['f'=>$form->createView()]);




    }


    /**
     * @Route("/Gmaps", name="Gmaps")
     */
    public function maps(EntityManagerInterface $entityManager): Response
    {

        $transports = $entityManager->getRepository(Revtransport::class)->findBy([
            "iduser" => 58 //idUser

        ]);
        return $this->render('transport/guideMaps.html.twig', [
            'b'=>$transports
        ]);
    }


   /** public function searchTransport(Request $request, NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(transport::class);
        $requestString = $request->get('searchValue');
        $transport = $repository->findHebergementByAddress($requestString);
        $jsonContent = $Normalizer->normalize($transport, 'json',[]);

        return new Response(json_encode($jsonContent));




}*/}