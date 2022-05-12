<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\ReserEvenement;
use App\Form\EvenementType;
use App\Repository\EvennementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Json;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/evenement")
 */
class EvenementController extends AbstractController
{
    /**
     * @Route("/", name="app_evenement_index", methods={"GET", "POST"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager, EvennementRepository $evenmentRepository): Response
    {
        $evenements = $evenmentRepository->findAll();
        // Retrieve the entity manager of Doctrine
        $order = $request->get('type');
        if ($order == "Décroissant") {
            $evenements = $evenmentRepository->triDecroissant();
//            dd($evenements);

        } else {
            $evenements = $evenmentRepository->triCroissant();
//            dd($evenements);

        }

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    /**
     * @Route("/indexf/{page<\d+>}", name="app_evenement_indexf", methods={"GET"})
     */
    public function indexf(Request $request, EvennementRepository $evennementRepository,PaginatorInterface $paginator, int $page = 1): Response
    {
//        $donnees = $this->getDoctrine()->getRepository(Evenement::class)->findAll();
        $donnees = $evennementRepository->findAllEvents();
        $paged = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
        $pagerfanta = new Pagerfanta(new QueryAdapter($donnees));
        $pagerfanta->setMaxPerPage(2);
        $pagerfanta->setCurrentPage($page);

        return $this->render('evenement/indexf.html.twig', [
            'evenements' => $pagerfanta,
        ]);
    }

    /**
     * @Route("/liste", name="List_evennement")
     */
    public function listj(EvennementRepository $evenmentRepository)
    {   $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $events = $evenmentRepository->findAllEvents();
        $events = $events->getQuery()->getResult();


        // Retrieve the HTML generated in our twig file
        $html = $this->render('evenement/liste.html.twig',['evenements'=>$events]);
//        return $this->render('evenement/liste.html.twig',['evenements'=>$events]);

        // Load HTML to Dompdf
//        $html .= '<link type="text/css" href="/absolute/path/to/pdf.css" rel="stylesheet" />';
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);



    }

    /**
     * @Route("/new", name="app_evenement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if($image){
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $evenement->setImage($newFilename);
            }
//            dd($evenement);
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/triHH", name="nidhal",methods={"GET", "POST"})
     */
    public function Tri(Request $request, EvennementRepository $evenmentRepository): Response
    {
        $evenements = $evenmentRepository->findAll();
        // Retrieve the entity manager of Doctrine
        $order = $request->get('type');
        if ($order == "Croissant") {
            $evenements = $evenmentRepository->triCroissant();
            dd($evenements);
        } else {

            $evenements = $evenmentRepository->triDecroissant();
            dd($evenements);
        }

        // Render the twig view
        return $this->render('evenement/index.html.twig', ['evenement' => $evenements
        ]);
    }

    /**
     * @Route("/{idevent}", name="app_evenement_show", methods={"GET"})
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    /**
     * @Route("/{idevent}/edit", name="app_evenement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if($image){
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $evenement->setImage($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idevent}", name="app_evenement_delete", methods={"POST"})
     */
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getIdevent(), $request->request->get('_token'))) {
            $res = $this->getDoctrine()->getRepository(ReserEvenement::class)->findBy(['idevent'=>$evenement->getIdevent()]);
            foreach ($res as $r){
                $entityManager->remove($r);
            }
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/evenement/ajax_search", name="ajax_search" ,methods={"GET"})
     * @param Request $request
     * @param EvennementRepository $EvennementRepository
     * @return Response
     */
    public function searchAction(Request $request,EvennementRepository $evennementRepository) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $evenements =$evennementRepository->SearchNom($requestString);
        if(!$evenements) {
            $result['evenements']['error'] = "evenements non trouvée ";
        } else {
            $result['evenements'] = $this->getRealEntities($evenements);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($evenements){
        foreach ($evenements as $evenement){
            $realEntities[$evenement->getId()] = [$evenement->getImage(),$evenement->getNom()];

        }
        return $realEntities;
    }
    /**
     * @Route("/recherche/eve", name="evenement_search")
     */
    public function recherche(Request $request, EvennementRepository $evennementRepository){
        $data=$request->request->get('data');
        $evenement=$evennementRepository->reche($data);
        return $this->render('evenement/index.html.twig', [
            'evenements' =>  $evenement,
            'data'=>$data


        ]);


    }

    // TODO ajout evennement

    /**
     * @Route("/ajouter_mobile", name="product_new", methods={"GET", "POST"})
     */
    public function ajouter_mobile(Request $request, NormalizerInterface $Normalizer,EvennementRepository  $repository )
    {
        $entityManager = $this->getDoctrine()->getManager();
        $even = new Evenement();

        $even->setNom($request->query->get("Nom"));
       $even->setDateDebut($request->query->get("dateDebut"));
      $even->setDateFin($request->query->get("datefin"));
        $even->setLieu($request->query->get("lieu"));
        $even->setDescription($request->query->get("description"));
        $even->setStatus($request->query->get("status"));
        $even->setImage("d");
        $even->setCapacite($request->query->get("capacite"));
        $even->setId($request->query->get("User"));
        $commande->setUser($request->get('user'));
        $entityManager->persist($even);
        $entityManager->flush();

        $jsonContent=$Normalizer->normalize($even,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
    // TODO modifier evennement
/**
 * @Route("/updateevenement", name="update_eve")
 *
 */
    public function modifierevmAction(Request $request,NormalizerInterface $Normalizer) {
        $em = $this->getDoctrine()->getManager();
        $cmnt = $this->getDoctrine()->getManager()
            ->getRepository(Evenement::class)
            ->find(23);





        $cmnt->setNom($request->query->get("Nom"));
        $cmnt->setDateDebut($request->query->get("dateDebut"));
        $cmnt->setDateFin($request->query->get("datefin"));
        $cmnt->setLieu($request->query->get("lieu"));
        $cmnt->setDescription($request->query->get("description"));
        $cmnt->setStatus($request->query->get("status"));
        $cmnt->setImage($request->query->get(" image"));
        $cmnt->setCapacite($request->query->get("capacite"));
        $cmnt->setId($request->query->get("User"));

        $em->persist($cmnt);
        $em->flush();
        $json=$Normalizer->normalize($cmnt,'json',['groups'=>'post:read']);

        return new JsonResponse("cmnt a ete modifiee avec success.");

    }





       /**
        * @Route("/displayEvenement", name="display_eve")
        */
      public function allRecAction(SerializerInterface $serializer):Response
    {
        // On récupère la liste des articles
        $articles = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();

        // On spécifie qu'on utilise l'encodeur JSON
        $encoders = [new JsonEncoder()];

        // On instancie le "normaliseur" pour convertir la collection en tableau
        $normalizers = [new ObjectNormalizer()];

        // On instancie le convertisseur
        $serializer = new Serializer($normalizers, $encoders);

        // On convertit en json
        $jsonContent = $serializer->serialize($articles, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getIdevent;
            }
        ]);

        // On instancie la réponse
        $response = new Response($jsonContent);

        // On ajoute l'entête HTTP
        $response->headers->set('Content-Type', 'application/json');

        // On envoie la réponse
        return $response;

    }

    // TODO mobile delete evennement fonctionnant

/**
 * @Route("/deleteEvenementMobile/{idevent}", name="delete_evenement_mobile")
 *
 */

      public function deleteEvenementMobile(Request $request, Evenement $evenement, EntityManagerInterface $entityManager) {
//        $id = $request->get("id");

//        $rep=$this->getDoctrine()->getRepository(Evenement::class);
        $em=$this->getDoctrine()->getManager();
//        $cmnt=$rep->find($id);
//        if($cmnt!=null ) {
//            $res = $this->getDoctrine()->getRepository(ReserEvenement::class)->findBy(['idevent'=>$evenement->getIdevent()]);
//            foreach ($res as $r){
//                $entityManager->remove($r);
//            }
//            $entityManager->remove($evenement);
//            $entityManager->flush();
//
//            $serialize = new Serializer([new ObjectNormalizer()]);
//            $formatted = $serialize->normalize("cmnt a ete supprimee avec success.");
//            return new JsonResponse($formatted);
//
//        }
          $res = $this->getDoctrine()->getRepository(ReserEvenement::class)->findBy(['idevent'=>$evenement->getIdevent()]);
          foreach ($res as $r){
              $entityManager->remove($r);
          }
          $entityManager->remove($evenement);
          $entityManager->flush();
          $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("cmnt a ete supprimee avec success.");
            return new JsonResponse($formatted);
//        return new JsonResponse("id cmnt invalide.");


    }







}
