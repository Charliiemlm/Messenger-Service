<?php

namespace App\Controller;

use App\Entity\Messenger;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mime\Message;

class index extends AbstractController
{
    public function index(EntityManagerInterface $em, UserInterface $user): Response
    {

        $messagesRepository = $em->getRepository(Messenger::class);

        /*  $userRepository=$em->getRepository(User::class);

        $messages=$messagesRepository->findAll();
        $users=$userRepository->findAll();    */

        /*  $idUser = $user->getId();

        #consulta buscar mensajes enviados
        $query = $messagesRepository->createQueryBuilder('m')
            ->where('m.idUsers = :idUser')
            ->setParameter('idUser', $idUser)
            ->getQuery();
        $messages = $query->getResult();

 */
        #consulta buscar mensajes recibidos
        $email = $user->getEmail();
        $query = $messagesRepository->createQueryBuilder('m')
            ->where('m.destinatary = :email')
            ->setParameter('email', $email)
            ->orderBy('m.time', 'DESC')
            ->getQuery();
        $messages = $query->getResult();


        return $this->render('messages/index.html.twig', [
            'messages' => $messages,
        ]);
    }



    public function checkMessage(EntityManagerInterface $em, Request $request, $id)
    {

        $messagesRepository = $em->getRepository(Messenger::class);
        $id = $request->attributes->get('id'); #recoger con el nombre que se envie por la url
        $query = $messagesRepository->createQueryBuilder('m')
            ->where('m.id = :id')
            ->setParameter('id', $id)
            ->getQuery();
        $result = $query->getResult();

        #cambiar mensaje a visto
        $message = $messagesRepository->find($id)->setIsChecked(true);
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
        $emailFrom = $user->getEmail();
        $message = new Messenger();
        $form = $this->createFormBuilder($message)
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


        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            var_dump($message);

            $em->persist($message);
            $em->flush();
            //sesion flash
            $session = new Session();
            $session->getFlashBag()->add('message', 'Mensaje enviado correctamente');
            return $this->redirectToRoute('sendMessage');
        }


        return $this->render('messages/sendMessage.html.twig', [

            'form' => $form->createView()

        ]);
    }

}
