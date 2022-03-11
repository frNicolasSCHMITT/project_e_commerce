<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\UserRepository;
use App\Entity\User;

/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security )
    {
        $this->security = $security;
    }

    // /**
    //  * @Route("/", name="app_comment_index", methods={"GET"})
    //  */
    // public function index(CommentRepository $commentRepository): Response
    // {
    //     return $this->render('comment/index.html.twig', [
    //         'comments' => $commentRepository->findAll(),
    //     ]);
    // }

    // /**
    //  * @Route("/new", name="app_comment_new", methods={"GET", "POST"})
    //  */
    // public function new(Request $request, CommentRepository $commentRepository): Response
    // {
    //     $comment = new Comment();
    //     $form = $this->createForm(CommentType::class, $comment);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $commentRepository->add($comment);
    //         return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('comment/new.html.twig', [
    //         'comment' => $comment,
    //         'form' => $form,
    //     ]);
    // }

    // /**
    //  * @Route("/{id}", name="app_comment_show", methods={"GET"})
    //  */
    // public function show(Comment $comment): Response
    // {
    //     return $this->render('comment/show.html.twig', [
    //         'comment' => $comment,
    //     ]);
    // }

    /**
     * @Route("/{id}/edit", name="app_comment_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Comment $comment, CommentRepository $commentRepository, AuthenticationUtils $authenticationUtils, UserRepository $userRepository): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->add($comment);
            


            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
                
            $loggedUser = $this->security->getUser();

            $comments = $commentRepository->findBy(
                ['User' => $loggedUser],
                ['postDate' => 'ASC']
            );  

            $users = $userRepository->findBy(
                ['email' => $loggedUser->getUserIdentifier()]
            );

            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();


            return $this->render('core/account.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'comments' => $comments, 'user' => $loggedUser]);
        }

        return $this->renderForm('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_comment_delete", methods={"POST"})
     */
    public function delete(Request $request, Comment $comment, CommentRepository $commentRepository, AuthenticationUtils $authenticationUtils, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $commentRepository->remove($comment);
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
            
        $loggedUser = $this->security->getUser();

        $comments = $commentRepository->findBy(
            ['User' => $loggedUser],
            ['postDate' => 'ASC']
        );  

        $users = $userRepository->findBy(
            ['email' => $loggedUser->getUserIdentifier()]
        );

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('core/account.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'comments' => $comments, 'user' => $loggedUser]);        
    }
}
