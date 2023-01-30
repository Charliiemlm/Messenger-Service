<?php

namespace App\Controller;

use App\Entity\Messenger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\HttpFoundation\Session\Session;

class index extends AbstractController
{
    public function index(EntityManagerInterface $em, UserInterface $user): Response
    {

        $messagesRepository = $em->getRepository(Messenger::class);#creamos repositorio
        #consulta buscar mensajes recibidos
        $email = $user->getEmail();#obtenemos email del usuario actual con UserInterface
        $query = $messagesRepository->createQueryBuilder('m')#creamos consulta
            ->where('m.destinatary = :email')
            ->setParameter('email', $email)
            ->orderBy('m.time', 'DESC')
            ->getQuery();
        $messages = $query->getResult(); #guardamos datos consulta


        return $this->render('messages/index.html.twig', [ #enviamos datos a la vista
            'messages' => $messages,
        ]);
    }



    public function checkMessage(EntityManagerInterface $em, Request $request, $id)
    {

        $messagesRepository = $em->getRepository(Messenger::class);#repo
        $id = $request->attributes->get('id'); #recoger con el nombre que se envie por la url
        $query = $messagesRepository->createQueryBuilder('m')#consulta
            ->where('m.id = :id')
            ->setParameter('id', $id)
            ->getQuery();
        $result = $query->getResult(); #resultSet

        #cambiar mensaje a visto
        $message = $messagesRepository->find($id)->setIsChecked(true);
        #confirmamos cambios en la bbdd
        $em->persist($message);
        $em->flush();


        return $this->render('messages/checkMessage.html.twig', [
            'messages' => $result,
        ]);
    }
    public function deleteMessage(EntityManagerInterface $em, Request $request, $id)
    {

        $messagesRepository = $em->getRepository(Messenger::class); #repo de todos los mensajes
        $id = $request->attributes->get('id'); #recoger id del mensaje enviado por la url
        $message = $messagesRepository->find($id); #encontrar mensaje concreto 
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute('mainPage');
    }


    public  function sendMessage(EntityManagerInterface $em, UserInterface $user, Request $request)
    {
        $emailFrom = $user->getEmail();#email del usuario actual
        
        $message = new Messenger();#clase 
        $form = $this->createFormBuilder($message)#crear form
            #->setAction($this->generateUrl('guardar'))
            ->setMethod('POST')
            ->add('destinatary', TextType::class, [
                'label' => 'Destinatary',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('subject', TextType::class, [
                'label' => 'Subject',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'message',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            ->add('emailFrom', HiddenType::class, [
                'data' => $emailFrom
            ])
            ->add('time', HiddenType::class, [
                'data'  => (new \DateTime())->format('Y-m-d H:i:s')
            ])
            ->add('isChecked', HiddenType::class, [
                'data'  => 0
            ])
    
            ->getForm();


        #comprobamos que el formulario se envio
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $em->persist($message);
            $em->flush();
            //sesion flash para monstrar mensaje 
            $session = new Session();
            $session->getFlashBag()->add('message', 'Mensaje enviado correctamente');
            return $this->redirectToRoute('sendMessage');
        }

        #enviar form a la vista
        return $this->render('messages/sendMessage.html.twig', [

            'form' => $form->createView()

        ]);
    }

    #logout
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    #Pagina principal
    #[Route(path: '/', name: 'optionsUser')]
    public function optionsUser()
    {
        return $this->render('user/mainPage.html.twig');
    }

}
