<?php

namespace App\Controller;

use App\Entity\Hebergement;
use App\Form\HebergementType;
use App\Repository\HebergementRepository;
use App\Services\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/hebergement")
 */
class HebergementController extends AbstractController
{

    /**
     * @Route("/", name="app_hebergement_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $data=$request->getSession()->get('Data');
        $donnees = $this->getDoctrine()->getRepository(Hebergement::class)->findAll();

        $heberAddress = [];
        $heberCap = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($donnees as $hebergement) {
            $heberAddress[] = $hebergement->getAddress();
            $heberCap[] = $hebergement->getcapacitechambre();
        }
        $hebergement = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            4
        );
        return $this->render('hebergement/index.html.twig', [
            'hebergement' => $hebergement,
            'heberAddress' => json_encode($heberAddress),
            'heberCap' => json_encode($heberCap),
            'data'=>$data

        ]);
    }

    /**
     * @Route("/new", name="app_hebergement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ImageUploader $imageUploader): Response
        {
            $data=$request->getSession()->get('Data');
            $hebergement = new Hebergement();
            $form = $this->createForm(HebergementType::class, $hebergement);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $imageFile = $form->get('photo')->getData();
                if ($imageFile) {
                    $imageFileName = $imageUploader->upload($imageFile);
                    $hebergement->setPhoto($imageFileName);
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($hebergement);
                $entityManager->flush();

                return $this->redirectToRoute('app_hebergement_index');
            }

        return $this->render('hebergement/new.html.twig', [
            'hebergement' => $hebergement,
            'data'=>$data,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idheberg}", name="app_hebergement_show", methods={"GET"}, requirements={"idheberg":"\d+"})
     */
    public function show(Hebergement $hebergement,Request $request): Response
    {
        $data=$request->getSession()->get('Data');

        return $this->render('hebergement/show.html.twig', [
            'hebergement' => $hebergement,
            'data'=>$data
        ]);
    }

    /**
     * @Route("/{idheberg}/edit", name="app_hebergement_edit", methods={"GET", "POST","DELETE"})
     */
    public function edit(Request $request, Hebergement $hebergement,ImageUploader $imageUploader): Response
    {             $data=$request->getSession()->get('Data');

        $fileName = $hebergement->getPhoto();
        $hebergement->setPhoto(
            new File($this->getParameter('images_directory').'/'.$hebergement->getPhoto())
        );
        $form = $this->createForm(HebergementType::class, $hebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $brochureFile = $form->get('photo')->getData();
            if ($brochureFile) {
                $brochureFileName = $imageUploader->upload($brochureFile);
                $hebergement->setPhoto($brochureFileName);
            } else {
                $hebergement->setPhoto($fileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_hebergement_index');
        }

        return $this->render('hebergement/edit.html.twig', [
            'hebergement' => $hebergement,
            'form' => $form->createView(),
            'data'=>$data,

        ]);
    }

    /**
     * @Route("/{idheberg}", name="app_hebergement_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Hebergement $hebergement, EntityManagerInterface $entityManager): Response
    {
        $data=$request->getSession()->get('Data');

        if ($this->isCsrfTokenValid('delete'.$hebergement->getIdheberg(), $request->request->get('_token'))) {
            $entityManager->remove($hebergement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_hebergement_index', ['data'=>$data], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/searchxx", name="search_heber", requirements={"idheberg":"\d+"})
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function searchHebergements(Request $request, NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Hebergement::class);
        $requestString = $request->get('searchValue');
        $hebergement = $repository->findHebergementByAddress($requestString);
        $jsonContent = $Normalizer->normalize($hebergement, 'json',[]);

        return new Response(json_encode($jsonContent));
    }
}
