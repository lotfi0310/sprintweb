<?php

namespace App\Controller\BackOffice;
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


class showf extends AbstractController
{
    /**
     * @Route("/fronthomeh", name="app_frontoffice")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
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
            2
        );
        return $this->render('SelectHebergement/show.html.twig', [
            'hebergement' => $hebergement,
            'heberAddress' => json_encode($heberAddress),
            'heberCap' => json_encode($heberCap),
        ]);
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
