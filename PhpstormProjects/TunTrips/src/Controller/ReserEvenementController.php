<?php

namespace App\Controller;

use App\Entity\ReserEvenement;
use App\Entity\User;
use App\Form\ReserEvenementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;

/**
 * @Route("/reser/evenement")
 */
class ReserEvenementController extends AbstractController
{
    /**
     * @Route("/", name="app_reser_evenement_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reserEvenements = $entityManager
            ->getRepository(ReserEvenement::class)
            ->findAll();

        return $this->render('reser_evenement/index.html.twig', [
            'reser_evenements' => $reserEvenements,
        ]);
    }

    /**
     * @Route("/new", name="app_reser_evenement_new", methods={"GET", "POST"})
     */
    public function new(MailerInterface $mailer, Request $request, EntityManagerInterface $entityManager): Response
    {
        $reserEvenement = new ReserEvenement();
        $form = $this->createForm(ReserEvenementType::class, $reserEvenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $reserEvenement->getId();
            $email = (new TemplatedEmail())
                ->from('nidhal.sassi@esprit.tn')
                ->to($user->getEmail())
                ->subject('Welcome To Tunitrips')
//                ->text('you just submitted to a reservation')
                ->htmlTemplate('user_email/reservation.html.twig')
                ->context([
                    'user'=>$user
                ])
            ;
            $mailer->send($email);
//            dd($reserEvenement->getId()->getEmail());

            $entityManager->persist($reserEvenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_reser_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reser_evenement/new.html.twig', [
            'reser_evenement' => $reserEvenement,
            'form' => $form->createView(),
        ]);
    }


    // TODO mobile affichage en erreur
    /**
     * @Route("/displayReservation", name="display_reser")
     */
    public function allRecAction(SerializerInterface  $Serializer):Response
    {
//        // On récupère la liste des articles
//        $articles = $this->getDoctrine()->getManager()->getRepository(ReserEvenement::class)->findAll();

//        // On spécifie qu'on utilise l'encodeur JSON
//        $encoders = [new JsonEncoder()];

//        // On instancie le "normaliseur" pour convertir la collection en tableau
//        $normalizers = [new ObjectNormalizer()];

//        // On instancie le convertisseur
//        $serializer = new Serializer($normalizers, $encoders);
//
//        // On convertit en json
//        $jsonContent = $serializer->serialize($articles, 'json', [
//            'circular_reference_handler' => function ($object) {
//                return $object->getId();
//            }
//        ]);
//        // On instancie la réponse
//        $response = new Response($jsonContent);
//        // On ajoute l'entête HTTP
//        $response->headers->set('Content-Type', 'application/json');
        $articles = $this->getDoctrine()->getManager()->getRepository(ReserEvenement::class)->findAll();
//        dd($articles);
        $jsonContent = $Serializer->serialize($articles, 'json');
//        $serialize = new Serializer([new ObjectNormalizer()]);
     //   $formatted = $Serializer->serialize($articles, 'json');
//        dd($formatted);

        // On envoie la réponse
        return new Response(json_encode($jsonContent));

    }


    /**
     * @Route("/{idReser}", name="app_reser_evenement_show", methods={"GET"})
     */
    public function show(ReserEvenement $reserEvenement): Response
    {
        return $this->render('reser_evenement/show.html.twig', [
            'reser_evenement' => $reserEvenement,
        ]);
    }

    /**
     * @Route("/{idReser}/edit", name="app_reser_evenement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ReserEvenement $reserEvenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReserEvenementType::class, $reserEvenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reser_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reser_evenement/edit.html.twig', [
            'reser_evenement' => $reserEvenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idReser}", name="app_reser_evenement_delete", methods={"POST"})
     */
    public function delete(Request $request, ReserEvenement $reserEvenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reserEvenement->getIdReser(), $request->request->get('_token'))) {
            $entityManager->remove($reserEvenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reser_evenement_index', [], Response::HTTP_SEE_OTHER);
    }

    //TODO: ajout mobile, en erreur

    /**
     * @Route("/ajouter_Reservation_mobile", name="AjouterreservationMobile")
     * @Method("POST")
     */
    public function Ajouter_Reservation_mobile(Request $request): Response

    {
        $frm = new ReserEvenement();
        $dateReservation = $request->query->get("dateReservation");
        $id= $request->query->get("User");
        $idevent=$request->query->get("Evenement");



        $em = $this->getDoctrine()->getManager();


        $frm->setDateReservation($dateReservation);
        $frm->setId($id);
        $frm->setIdevent($idevent);


        $em->persist($frm);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($frm);
        return new JsonResponse($formatted);


    }
    /**
     * @Route("/updatereservation", name="update_reservation")
     *
     */
    public function modifierReserAction(Request $request,NormalizerInterface $Normalizer) {
        $em = $this->getDoctrine()->getManager();
        $cmnt = $this->getDoctrine()->getManager()
            ->getRepository(ReserEvenement::class)
            ->find($request->get("id"));




        $cmnt->setId($request->query->get("User"));
        $cmnt->setIdevent($request->get("idevent"));
        $cmnt->setDateReservation($request->get("dateReservation"));




        $em->persist($cmnt);
        $em->flush();
        $json=$Normalizer->normalize($cmnt,'json',['groups'=>'post:read']);

        return new JsonResponse("cmnt a ete modifiee avec success.");

    }






    /**
     * @Route("/deleteReservationMobile", name="delete_reservation_mobile")
     *
     */

    public function deleteReservationMobile(Request $request) {
        $idReser = $request->get("idReser");

        $rep=$this->getDoctrine()->getRepository(ReserEvenement::class);
        $em=$this->getDoctrine()->getManager();
        $cmnt=$rep->find($idReser);
        if($cmnt!=null ) {
            $em->remove($cmnt);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("cmnt a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id cmnt invalide.");


    }







}
