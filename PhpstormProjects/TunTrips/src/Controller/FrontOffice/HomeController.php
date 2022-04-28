<?php

namespace App\Controller\FrontOffice;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;



class HomeController extends AbstractController
{
    /**
     * @Route("/" , name="home")
     */
    public function index(Request $request)
    {
        return $this->render('front_office/default/index.html.twig', [
            'controller_name' => 'HomeController'
        ]);
    }
    /**
     * @Route("/newrec", name="app_reclamation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $userRepository,EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        $data=$request->getSession()->get('Data');
        $id=$data->getId();
        $user=$userRepository->find($id);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setId($user);
            $reclamation->setDate(new \DateTime('now'));
            $reclamation->setEtat(false);
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('userrec', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front_office/default/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }


}
