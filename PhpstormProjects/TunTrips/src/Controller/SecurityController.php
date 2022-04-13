<?php

namespace App\Controller;

use App\Entity\Codevalidation;
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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request,EntityManagerInterface  $manager,UserPasswordEncoderInterface $encoder,MailerInterface $mailer, TokenStorageInterface $tokenStorage)
    {
        $user=new User();
        $form=$this->createForm(RegistrationType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $hash=$encoder->encodePassword($user,$user->getPasswd());
            $user->setPasswd($hash);

            $file = $form->get('photo')->getData();

            $file_name=md5(uniqid()).'.'.$file->guessExtension();
            try {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$file->guessExtension();
             $file->move(
               $this->getParameter('images_directory'),
                   $newFilename
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
                ->html('<a >123hfh4789 </a>');
            $token = new UsernamePasswordToken($user, $user->getPasswd(), 'main');
            $tokenStorage->setToken($token);

            $mailer->send($email);
            $code =new Codevalidation();
            $code->setCode('123hfh4789');
            //$user->setEmail($form->getName());
            //$code->setEmail($user);
            $manager->persist($code);
            $manager->flush();
            return $this->redirectToRoute('validation');
        }
        return $this->render('security/registration.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $utils): Response
    {
        return $this->render('security/login.html.twig', [
            'last_username' => $utils->getLastUsername(),
            'error' => $utils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route ("/uservalidation",name="validation")
     */
    public function validationCompte(){

        return $this->render('front_office/default/validation_compte.html.twig');

    }
}
