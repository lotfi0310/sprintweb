<?php

namespace App\Controller;

use App\Controller\FrontOffice\HomeController;
use App\Entity\Codevalidation;
use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegistrationType;
use App\Repository\CodevalidationRepository;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use MongoDB\Driver\Session;
use mysql_xdevapi\Schema;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use function PHPUnit\Framework\isNull;


/**
 * @Route ("/fronthome")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, MailerInterface $mailer)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $hash=md5($user->getPasswd());
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
            $c = $this->generateRandomString();
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
     * @Route ("/login",name="login")
     */
    public function Login (Request $request, UserRepository $userRepository,SessionInterface $session) :Response
    {

        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $mail= $user->getEmail();
            $pass= $user->getPasswd();

            if($mail!="" && $pass!="") {
                $exist=$userRepository->findOneBy(['email' =>$mail]);
                $us =$userRepository->findOneBy(['email' => $mail,'passwd'=>md5($pass)]);
                if ($exist!=null){
                    if ($us!=null){
                        if ($us->getValide()==1){
                            if($us->getRole()=="ROLE_USER")
                            {
                                $session->set('Data',$us);
                                return $this->redirectToRoute('dashborduser');

                            }
                            else if($us->getRole()=="ROLE_FOURNISSEUR")
                            {
                                $session->set('Data',$us);

                                return $this->redirectToRoute('dashbordfounisseur');

                            }
                            else if($us->getRole()=="ROLE_ADMIN") {
                                $session->set('Data',$us);
                                $session->getMetadataBag()->stampNew(36000);
                                return $this->redirectToRoute('dashbordadmin');
                            }

                        }
                        else{
                            return $this->redirectToRoute('validation',['id' => $us->getId()]);

                         }


                    }
                    else{
                        return $this->redirectToRoute('recuperepass');
                    }

                }else {
                    return $this->redirectToRoute('security_registration');
                }



            }
            return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('security/login.html.twig',['formL' => $form->createView()]);
    }



    /**
     * @Route ("/dashboard/user",name="dashborduser")
     */
    public function dashborduser(Request $request):Response
    {

        $data=$request->getSession()->get('Data');
        return $this->render('Redirection/dashbord/user_dashbord.html.twig',['data'=>$data]);

    } /**
 * @Route ("/dashbord/founisseur",name="dashbordfounisseur")
 */
    public function dashbordfounisseur(Request $request):Response
    {

        $data=$request->getSession()->get('Data');
        return $this->render('Redirection/dashbord/fournisseur_dashbord.html.twig',['data'=>$data]);

    }

    /**
     * @Route ("/admin",name="dashbordadmin")
     */
    public function dashbordAdmin(Request $request):Response
    {
        $data=$request->getSession()->get('Data');
        return $this->render('back_office/default/index.html.twig',['data'=>$data]);

    }


    /**
     * @Route ("/edituserprofil", name="editprofileuser")
     */
    public function editprofil( EntityManagerInterface $em,Request $request,SessionInterface $session): Response
    {

        $data=$request->getSession()->get('Data');
        $id=$request->request->get('iduser');
        $name=$request->request->get('first_name');
        $email=$request->request->get('email');
        $pass=$request->request->get('p');
        $valide=$request->request->get('valide');
        $etat=$request->request->get('etat');
        $role=$request->request->get('role');
        $last=$request->request->get('last_name');
        $num=$request->request->get('phone');
        $photo=$request->request->get('photo');
        $country=$request->request->get('country');



        if ($id!=null){
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find((int)$id);


           $user->setNom((string)$name);
           $user->setPrenom((string)$last);
            $user->setEmail((string)$email);
            $user->setValide((boolean)$valide);
            $user->setEtat((boolean)$etat);
            $user->setPasswd((string)$pass);
            $user->setNumTel((string)$num);
            $user->setCountry((string)$country);
            $user->setRole((string)$role);

           $em=$this->getDoctrine()->getManager();
           $em->flush();
            $session->set('Data',$user);
            return $this->redirectToRoute('dashborduser',['data'=>$user]);

        }

        return $this->render('user/editprofil.html.twig',['data'=>$data]);

    }

    /**
     * @Route ("/logout",name="logout")
     */
    public function logout(AuthenticationUtils $authenticationUtils,Request $request)
    {
        $request->getSession()->remove();
        return $this->render('security/login.html.twig');

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

           return $this->redirectToRoute('home');

        } else {
            return $this->redirectToRoute('validation',[
                'id'=>$id
            ]);

        }
        return new Response('success');

    }

    function generateRandomString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @Route("/userrec", name="userrec")
     */
    public function userreclamation(ReclamationRepository $reclamationRepository,Request $request): Response
    {
        $data=$request->getSession()->get('Data');
        $reclamation = $reclamationRepository->findrecExampleField($data->getId());

        return $this->render('reclamation/user_rec.html.twig',['reclamation'=>$reclamation]);
    }


    /**
     * @Route ("/recuperepass",name="recuperepass")
     */
    public function recupererpass(Request $request,  MailerInterface $mailer):Response
    {
        $email=$request->request->get('email');
        if ($email!=null)
        {
            $code=$this->generateRandomString();

            $coder = $this->getDoctrine()
                ->getRepository(Codevalidation::class)
                ->findOneBy(['email'=>$email]);
            $coder->setCoderecMp($code);
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            $mail = (new Email())
                ->from('tuntrips2022@gmail.com')
                ->to($email)
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Code Recuperation password!')
                ->text('Recuperer votre mot de passe  ')
                ->html($code);


            $mailer->send($mail);
           return $this->redirectToRoute('coderecrecu');
        }

        return $this->render('front_office/default/recuperer_pass.html.twig',['email'=>$email]);
    }


    /**
     * @Route ("/coderecrecu",name="coderecrecu")
     */
    public function coderecrecu(CodevalidationRepository $cvr,Request $request,SessionInterface $session):Response
    {

        $coderecuperation=$request->request->get('coderecuperation');
        if ($coderecuperation!=null){
            $coder=$cvr->findOneBy(['coderecMp'=>$coderecuperation]);
            if ($coder!=null){
               $email= $coder->getEmail();
               $session->set('email_recuperation',$email);
               return $this->redirectToRoute('newpassword');
            }
        }
        return $this->render('front_office/default/entrer_code_recu.html.twig',['coderecuperation'=>$coderecuperation]);
    }


    /**
     * @Route ("/newpassword" , name="newpassword" )
     */
    public function newPassword(Request $request):Response
    {
        $email=$request->getSession()->get('email_recuperation');
       $pass1=$request->request->get('pass1');
        $pass2=$request->request->get('pass2');
if ($pass1!=null && $pass2!=null){
  if ($pass1==$pass2)
  {
      $user= $this->getDoctrine()
          ->getRepository(User::class)
          ->findOneBy(['email'=>(string)$email]);
      $em=$this->getDoctrine()->getManager();
      $user->setPasswd(md5(((string)$pass1)));
      $em->flush();
     return $this->redirectToRoute('login');
  }
}
        return $this->render('front_office/default/new_password.html.twig');

    }


}