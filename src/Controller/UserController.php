<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Comment;
use App\Entity\Article;
use App\Repository\CommentRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
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
    //  * @Route("/", name="app_user_index", methods={"GET"})
    //  */
    // public function index(UserRepository $userRepository): Response
    // {
    //     return $this->render('user/index.html.twig', [
    //         'users' => $userRepository->findAll(),
    //     ]);
    // }

    // /**
    //  * @Route("/new", name="app_user_new", methods={"GET", "POST"})
    //  */
    // public function new(Request $request, UserRepository $userRepository): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $userRepository->add($user);
    //         return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('user/new.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //     ]);
    // }

    // /**
    //  * @Route("/{id}", name="app_user_show", methods={"GET"})
    //  */
    // public function show(User $user): Response
    // {
    //     return $this->render('user/show.html.twig', [
    //         'user' => $user,
    //     ]);
    // }

    /**
     * @Route("/{id}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository, CommentRepository $commentRepository, AuthenticationUtils $authenticationUtils, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            
            $userRepository->add($user);
            

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

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }

        $session = new Session();   //create a new session for an un-logged user
        $session->invalidate();

        $articles = $articleRepository->findBy(
            ['available' => true],
            ['id' => 'ASC'],
        );

        return $this->render('core/index.html.twig', ['articles' => $articles]);
    }
}
