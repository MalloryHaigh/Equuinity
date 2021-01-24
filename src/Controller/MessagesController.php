<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessageReplyType;
use App\Form\SendMessageType;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
    /**
     * @Route("/inbox", name="inbox")
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {

        #TODO Calculate the number of new messages per user

        $theUser = $this->get('security.token_storage')->getToken()->getUser();

        // Fetch inbox for this user
        $messages = $this->getDoctrine()->getRepository(Messages::class)->findBy(
            ['to_user' => $theUser],
            ['message_status' => 'DESC', 'message_sent' => 'DESC']
        );

        $pagination = $paginator->paginate(
            $messages, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        // Generate the compose form
        //$editProfile = $this->createForm(EditProfileType::class, $this->getUser());


        $message = new Messages();
        $compose = $this->createForm(SendMessageType::class,$message);

        // Submit the new message. Make sure to set the date, the from user, and the status as UNREAD
        $compose->handleRequest($request);
        if ($compose->isSubmitted() && $compose->isValid()){

            // The current time
            $now = date('Y-m-d H:i:s');
            $message->setMessageSent(new DateTime($now));
            $message->setFromUser($this->get('security.token_storage')->getToken()->getUser());
            $message->setMessageStatus("UNREAD");
            $message->setMessageType("Message");

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash("success","Your message has been sent!");

            return $this->redirect($request->getUri());
        }

        return $this->render('messages/index.html.twig', [
            'messages' => $messages,
            'pagination' => $pagination,
            'compose' => $compose->createView(),
            'controller_name' => 'MessagesController',
        ]);
    }

    /**
     * @Route("/inbox/message/{id}", name="message")
     * @IsGranted("ROLE_USER")
     */
    public function message(Request $request, ?int $id): Response
    {
        // Get the message in question
        $msg = $this->getDoctrine()->getRepository(Messages::class)->find($id);

        $currentTitle = $msg->getMessageTopic();
        $newTitle = "RE: ".$currentTitle;

        $replyTo = $msg->getFromUser();

        // When this message is viewed, mark as READ
        $msg->setMessageStatus("READ");

        $em = $this->getDoctrine()->getManager();
        $em->persist($msg);
        $em->flush();

        $message = new Messages();

        $reply = $this->createForm(MessageReplyType::class,$message);

        $reply->handleRequest($request);
        if ($reply->isSubmitted() && $reply->isValid()) {
            $now = date('Y-m-d H:i:s');
            $message->setMessageSent(new DateTime($now));
            $message->setFromUser($this->get('security.token_storage')->getToken()->getUser());
            $message->setMessageStatus("UNREAD");
            $message->setMessageType("Message");
            $message->setMessageTopic($newTitle);
            $message->setToUser($replyTo);

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash("success","Your reply has been sent!");

            return $this->redirect($request->getUri());
        }

        return $this->render('messages/message.html.twig',[
            'message' => $msg,
            'title' => $newTitle,
            'reply' => $reply->createView(),
            'controller_name' => 'ForumController'
        ]);
    }

    /**
     * @Route("/inbox/message/delete/{id}", name="deleteMessage")
     * @IsGranted("ROLE_USER")
     */
    public function deleteMessage(?int $id): Response
    {
        // Get the message in question
        $msg = $this->getDoctrine()->getRepository(Messages::class)->find($id);
        $em = $this->getDoctrine()->getManager();

        $em->remove($msg);
        $em->flush();

        $this->addFlash("success","You deleted a message!");

        return $this->redirectToRoute('inbox');
    }
}
