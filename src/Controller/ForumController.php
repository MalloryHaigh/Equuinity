<?php

namespace App\Controller;

use App\Entity\Config;
use App\Entity\ForumReplies;
use App\Entity\ForumThreads;
use App\Entity\User;
use App\Form\EditPostType;
use App\Form\EditThreadType;
use App\Form\PostType;
use App\Form\ThreadType;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Forums;
use Symfony\Component\Validator\Constraints\Date;

class ForumController extends AbstractController
{
    /**
     * @Route("/forum", name="forum")
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {

        $forums = $this->getDoctrine()->getRepository(Forums::class);

        $info = $this->getDoctrine()->getRepository(Config::class)->find('1');
        $rules = $info->getForumRules();

        return $this->render('forum/index.html.twig', [
            'forums' => $forums->findAll(),
            'rules' => $rules,
            'controller_name' => 'ForumController',
        ]);
    }

    /**
     * @Route("/forum/{id}", name="board")
     * @IsGranted("ROLE_USER")
     */
    public function board(Request $request, PaginatorInterface $paginator, ?int $id): Response
    {
        $board = $this->getDoctrine()->getRepository(Forums::class)->find($id);
        $threads = $this->getDoctrine()->getRepository(ForumThreads::class)->findBy(
            ['board_id'=>$id],
            ['status'=>'DESC','last_updated'=>'DESC']
        );
        $thread = new ForumThreads();

        $pagination = $paginator->paginate(
            $threads, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );

        $info = $this->getDoctrine()->getRepository(Config::class)->find('1');
        $rules = $info->getForumRules();

        // Generate the form that allows us to post new threads in this board
        $newThread = $this->createForm(ThreadType::class, $thread);

        // Add thread
        $newThread->handleRequest($request);

        if ($newThread->isSubmitted() && $newThread->isValid()) {

            $data = $newThread->getData();

            dump($data);

            // The current time
            $now = date('Y-m-d H:i:s');

            // Set the poster ID, board ID, posted date, status = open
            $thread->setBoardId($id);
            $thread->setPostedDate(new DateTime($now));
            $thread->setLastUpdated(new DateTime($now));
            $thread->setStatus("Open");
            $thread->setPostedBy($this->get('security.token_storage')->getToken()->getUser());


            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($thread);
            $em->flush();

            $this->addFlash("success","You've posted a new thread!");

            return $this->redirect($request->getUri());

        }

        return $this->render('forum/board.html.twig',[
            'forum' => $board,
            'threads' => $threads,
            'rules' => $rules,
            'pagination' => $pagination,
            'newThread' => $newThread->createView(),
            'controller_name' => 'ForumController'
        ]);
    }

    /**
     * @Route("/forum/thread/{id}", name="thread")
     * @IsGranted("ROLE_USER")
     */
    public function post(Request $request, PaginatorInterface $paginator, ?int $id): Response
    {
        $thread = $this->getDoctrine()->getRepository(ForumThreads::class)->find($id);
        $theBoard = $thread->getBoardId();
        $getOP = $thread->getPostedBy();

        $info = $this->getDoctrine()->getRepository(Config::class)->find('1');
        $rules = $info->getForumRules();

        $board = $this->getDoctrine()->getRepository(Forums::class)->find($theBoard);
        $op= $this->getDoctrine()->getRepository(User::class)->find($getOP);

        $replies = $this->getDoctrine()->getRepository(ForumReplies::class)->findBy(
            ['thread_id'=>$id],
            ['posted_date'=>'ASC']
        );

        $pagination = $paginator->paginate(
            $replies, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        // Create the add reply form
        $newReply = new ForumReplies();
        $newPost = $this->createForm(PostType::class, $newReply);

        // Process the add new reply form
        $newPost->handleRequest($request);

        if ($newPost->isSubmitted() && $newPost->isValid()) {

            // The current time
            $now = date('Y-m-d H:i:s');

            // Set the poster ID, board ID, posted date, status = open
            $newReply->setThreadId($id);
            $newReply->setPostedDate(new DateTime($now));
            $newReply->setPostedBy($this->get('security.token_storage')->getToken()->getUser());

            $thread->setLastUpdated(new DateTime($now));

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($newReply);
            $em->flush();



            $this->addFlash("success","You've posted a new reply!");

            return $this->redirect($request->getUri());
        }

        return $this->render('forum/thread.html.twig',[
            'thread' => $thread,
            'op' => $op,
            'board' => $board,
            'rules' => $rules,
            'replies' => $replies,
            'pagination' => $pagination,
            'new' => $newPost->createView(),
            'controller_name' => 'ForumController'
        ]);
    }
    /**
     * @Route("/forum/edit/thread/{id}", name="editThread")
     * @IsGranted("ROLE_USER")
     */
    public function editThread(Request $request, ?int $id): Response
    {
        $thread = $this->getDoctrine()->getRepository(ForumThreads::class)->find($id);

        $edit = $this->createForm(EditThreadType::class,$thread);

        $info = $this->getDoctrine()->getRepository(Config::class)->find('1');
        $rules = $info->getForumRules();

        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        //Edit the post
        $editPost = $edit->handleRequest($request);

        if ($edit->isSubmitted() && $editPost->isValid()) {
            $data = $editPost->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();

            $this->addFlash("success","You've edited a thread!");

            return $this->redirect($request->getUri());
        }

        return $this->render('forum/editThread.html.twig',[
            'thread' => $thread,
            'current' => $currentUser,
            'rules' => $rules,
            'edit' => $edit->createView(),
            'controller_name' => 'ForumController'
        ]);
    }


    /**
     * @Route("/forum/edit/{id}", name="edit")
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, ?int $id): Response
    {
        $post = $this->getDoctrine()->getRepository(ForumReplies::class)->find($id);
        $theThread = $post->getThreadId();

        $thread = $this->getDoctrine()->getRepository(ForumThreads::class)->find($theThread);

        $editPost = $this->createForm(EditPostType::class,$post);

        $info = $this->getDoctrine()->getRepository(Config::class)->find('1');
        $rules = $info->getForumRules();

        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        //Edit the post
        $edit = $editPost->handleRequest($request);

        if ($edit->isSubmitted() && $edit->isValid()) {
            $data = $edit->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();

            $this->addFlash("success","You've edited a reply!");

            return $this->redirect($request->getUri());
        }

        return $this->render('forum/edit.html.twig',[
            'post' => $post,
            'thread' => $thread,
            'rules' => $rules,
            'current' => $currentUser,
            'editPost' => $editPost->createView(),
            'controller_name' => 'ForumController'
        ]);
    }


    /**
     * @Route("/forum/unstick/{id}", name="unstick")
     * @IsGranted("ROLE_ADMIN")
     */
    public function unstick(?int $id): Response
    {
        $thread = $this->getDoctrine()->getRepository(ForumThreads::class)->find($id);

        // Unstick the post in question

        $thread->setStatus("Open");
        $em = $this->getDoctrine()->getManager();
        $em->persist($thread);
        $em->flush();

        $this->addFlash("success","You've made this thread unsticky!");

        return $this->redirectToRoute('forum');
    }

    /**
     * @Route("/forum/stick/{id}", name="stick")
     * @IsGranted("ROLE_ADMIN")
     */
    public function stick(?int $id): Response
    {
        $thread = $this->getDoctrine()->getRepository(ForumThreads::class)->find($id);

        // Unstick the post in question

        $thread->setStatus("Sticky");
        $em = $this->getDoctrine()->getManager();
        $em->persist($thread);
        $em->flush();

        $this->addFlash("success","You've made this thread sticky!");

        return $this->redirectToRoute('forum');
    }

}
