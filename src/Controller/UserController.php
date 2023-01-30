<?php

namespace App\Controller;
use App\Form\RegistrationFormType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
#para utenticarse en el login
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    public function register(Request $request ,UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em)
    {
        $user=new User();
        $form=$this->createForm(RegistrationFormType::class,$user);
        

        #recibir los datos en el controlador para debugear
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            
            $em->persist($user);
            $em->flush($user);
            
            $session=new Session();
            $session->getFlashBag()->add('message','Se ha registrado correctamente');
    
            return $this->redirectToRoute('register');
        }

        return $this->render('user/register.html.twig',[
            'form' => $form->createView()
        ]); 
    
    }

 
    public function login(AuthenticationUtils $authenticationUtils )
    {
       #en caso de error en el login 
        $error=$authenticationUtils->getLastAuthenticationError();
        $lastUsername=$authenticationUtils->getLastUsername();

        #datos a la vista
        return $this->render('user/login.html.twig',array(
            'error'=> $error,
            'lastUser'=> $lastUsername
        ));
    }
}
