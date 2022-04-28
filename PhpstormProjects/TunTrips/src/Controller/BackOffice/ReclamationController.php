<?php

namespace App\Controller\BackOffice;

use App\Entity\Reclamation;
use App\Form\Reclamation1Type;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/dashbordadmin/reclamation")
 */
class ReclamationController extends AbstractController
{

    /**
     * @Route("/", name="app_reclamation_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager,Request $request): Response
    {
        $data=$request->getSession()->get('Data');
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();

        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
            'data'=>$data
        ]);
    }





    /**
     * @Route("/{idrec}", name="app_reclamation_show", methods={"GET"})
     */
    public function show(Reclamation $reclamation,Request $request): Response
    {
        $data=$request->getSession()->get('Data');
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
            'data'=>$data
        ]);
    }

    /**
     * @Route("/{idrec}/edit", name="app_reclamation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $data=$request->getSession()->get('Data');
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
            'data'=>$data
        ]);
    }

    /**
     * @Route("/delete/{idrec}", name="app_reclamation_delete", methods={"GET"})
     */
    public function delete(Reclamation $reclamation,Request $request,ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager): Response
    {
        $data=$request->getSession()->get('Data');
        $entityManager->remove($reclamation);
        $entityManager->flush();
        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }




}
