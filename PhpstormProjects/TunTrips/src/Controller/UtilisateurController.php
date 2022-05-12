<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UtilisateurController extends AbstractController
{

    public function index(): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    /**
     * @Route("loginmob/{email}/{passwd}",name="login_mob")
     */
    public function login(SerializerInterface $serializer,UserRepository $repository,$email,$passwd){
        $user=$repository->findOneBy(['email'=>$email,'passwd'=>md5($passwd)]);

           $json=$serializer->serialize($user,'json') ;

       return new Response($json);

    }

    /**
     * @Route ("/inscription/{nom}/{prenom}/{email}/{passwd}/{role}/{country}/{num_Tel}/{photo}", name="inscription")
     */
    public function inscription(Request $request,SerializerInterface $serializer,EntityManagerInterface $entityManager,$nom,$prenom
    ,$email,$passwd,$role,$country,$photo){
        $data=new User();
    $data->setNom($nom);
    $data->setPrenom($prenom);
    $data->setEmail($email);
    $data->setPasswd($passwd);
    $data->setRole($role);
    $data->setCountry($country);
    $data->setPhoto($photo);
    $data->setValide(0);
    $data->setEtat(1);
     $entityManager->persist($data);
     $entityManager->flush();
     return new Response("user added successfuly");
    }

}
