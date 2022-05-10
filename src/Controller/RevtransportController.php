<?php

namespace App\Controller;

use App\Entity\Revtransport;
use App\Form\RevtransportType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/revtransport")
 */
class RevtransportController extends AbstractController
{
    /**
     * @Route("/", name="app_revtransport_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $revtransports = $entityManager
            ->getRepository(Revtransport::class)
            ->findAll();

        return $this->render('revtransport/index.html.twig', [
            'b'=>$revtransports
        ]);
    }

    /**
     * @Route("/myrev", name="myrev")
     */
    public function myrev(EntityManagerInterface $entityManager): Response
    {

        $revtransports = $entityManager->getRepository(Revtransport::class)->findBy([
            "iduser" => 58 //idUser

        ]);
        return $this->render('revtransport/indexFront.html.twig', [
            'b'=>$revtransports
        ]);
    }
    /**
     * @Route("/maps", name="maps")
     */
    public function maps(EntityManagerInterface $entityManager): Response
    {

        $revtransports = $entityManager->getRepository(Revtransport::class)->findBy([
            "iduser" => 58 //idUser

        ]);
        return $this->render('revtransport/maps.html.twig', [
            'b'=>$revtransports
        ]);
    }


















    /**
     * @Route("/new/{id}", name="app_revtransport_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,$id): Response
    {
        $revtransport = new Revtransport();
        $form = $this->createForm(RevtransportType::class, $revtransport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $revtransport->setIdtransport($id);
            $revtransport->setIduser(8);//idUser



            $entityManager->persist($revtransport);
            $entityManager->flush();

            return $this->redirectToRoute('app_revtransport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('revtransport/new.html.twig', [
            'revtransport' => $revtransport,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_revtransport_show", methods={"GET"})
     */
    public function show(Revtransport $revtransport): Response
    {
        return $this->render('revtransport/show.html.twig', [
            'revtransport' => $revtransport,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="app_revtransport_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Revtransport $revtransport, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RevtransportType::class, $revtransport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_revtransport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('revtransport/edit.html.twig', [
            'revtransport' => $revtransport,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/editrev", name="editrev", methods={"GET", "POST"})
     */
    public function editrev(Request $request, Revtransport $revtransport, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RevtransportType::class, $revtransport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('myrev', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('revtransport/editFront.html.twig', [
            'revtransport' => $revtransport,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}", name="app_revtransport_delete", methods={"POST"})
     */
    public function delete(Request $request, Revtransport $revtransport, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$revtransport->getId(), $request->request->get('_token'))) {
            $entityManager->remove($revtransport);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_revtransport_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/newRev/{id}", name="newRev", methods={"GET", "POST"})
     */
    public function newRev(Request $request, EntityManagerInterface $entityManager,$id): Response
    {
        $revtransport = new Revtransport();
        $form = $this->createForm(RevtransportType::class, $revtransport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $revtransport->setIdtransport($id);
            $revtransport->setIduser(8);//idUser



            $entityManager->persist($revtransport);
            $entityManager->flush();

            return $this->redirectToRoute('transport/display_Front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('revtransport/newFront.html.twig', [
            'revtransport' => $revtransport,
            'form' => $form->createView(),
        ]);
    }




}
