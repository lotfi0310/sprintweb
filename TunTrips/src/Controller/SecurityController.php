<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request,EntityManagerInterface  $manager,UserPasswordEncoderInterface $encoder,MailerInterface $mailer)
    {
        $user=new User();
        $form=$this->createForm(RegistrationType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $hash=$encoder->encodePassword($user,$user->getPasswd());
            $user->setPasswd($hash);

            $file=$user->getPhoto();
            $file_name=md5(uniqid()).'.'.$file->guessExtension();
            try {
             $file->move(
               $this->getParameter('images_directory'),
                   $file_name
             );
            }catch (FileException $e){
        }
            $user->setPhoto($file_name);
            $manager->persist($user);
            $manager->flush();
            $email = (new Email())
                ->from('tuntrips2022@gmail.com')
                ->to($user->getEmail())
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Time for Symfony Mailer!')
                ->text('valider votre compte en appuions sur le lien ci dessous ')
                ->html('<a >123hfhf789 </a>');

            $mailer->send($email);
            return $this->redirectToRoute('app_home');
        }
        return $this->render('security/registration.html.twig', [
            'form'=>$form->createView()
        ]);
    }

}
