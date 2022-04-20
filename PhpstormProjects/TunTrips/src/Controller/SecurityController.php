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



/**
 * @Route ("/fronthome")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, MailerInterface $mailer)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPasswd());
            $user->setPasswd($hash);

            $file = $form->get('photo')->getData();

            $file_name = md5(uniqid()) . '.' . $file->guessExtension();
            try {

                $file->move(
                    $this->getParameter('images_directory'),
                    $file_name
                );
            } catch (FileException $e) {
            }
            $user->setPhoto($file_name);
            $user->setValide(false);
            $manager->persist($user);
            $manager->flush();
            $c = "aaa111bbb222";
            $email = (new Email())
                ->from('tuntrips2022@gmail.com')
                ->to($user->getEmail())
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Time for Symfony Mailer!')
                ->text('valider votre compte en appuions sur le lien ci dessous ')
                ->html($c);


            $mailer->send($email);
            $code = new Codevalidation();
            $code->setCode($c);
            $code->setEmail($user->getEmail());
            $manager->persist($code);
            $manager->flush();
            return $this->redirectToRoute('validation', ['id' => $user->getId()]);
        }
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastusername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'lastusername' => $lastusername

        ]);

    }

    /**
     * @Route ("/logout",name="logout")
     */
    public function logout()
    {
        return $this->render('front_office/default/index.html.twig');

    }

    /**
     * @Route ("/uservalidation/{id}",name="validation")
     */
    public function validationCompte($id)
    {

        return $this->render('front_office/default/validation_compte.html.twig', [
            'id' => $id
        ]);

    }

    /**
     * @Route ("/uservalidation1",name="validation1")
     */
    public function validation(Request $request,EntityManagerInterface $em)
    {

        $code = $request->request->get('code');
        $id = $request->request->get('id');
        $user = new User();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find((int)$id);
        $userCode = new Codevalidation();
        $u = $user->getEmail();
        $userCode = $this->getDoctrine()
            ->getRepository(Codevalidation::class)->findOneBy(['email' => $u]);

        if ((string)$code== $userCode->getCode()) {
         $user->setValide('true');
           $em->flush();

           return $this->redirect(' /fronthome');

        } else {
            return $this->redirectToRoute('validation',[
                'id'=>$id
            ]);

        }
        return new Response('success');

    }
}