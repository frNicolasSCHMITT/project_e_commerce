<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use App\Entity\Comment;
use App\Entity\Article;
use App\Entity\User;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Knp\Component\Pager\PaginatorInterface;

class CoreController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security )
    {
        $this->security = $security;
    }

    
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, ArticleRepository $articleRepository, PaginatorInterface $paginator): Response
    {
        $data = $articleRepository->findBy(
            ['available' => true],
            ['id' => 'ASC'],
        );

        $articles = $paginator->paginate(
            $data, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            15 // Nombre de résultats par page
        );

        return $this->render('core/index.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/index/{id}", name="show_article", requirements={"id"="\d+"}, methods={"GET", "POST"})
     */
    public function show( Article $article, CommentRepository $commentRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
            // dd(($this->security->getUser()));
            // dd($article);
        $comments = $commentRepository->findBy(
            ['Article' => $article],
            ['postDate' => 'ASC']
        );

            // $time = date('Y/m/d H:i:s');
        $time = new \DateTime();
        $comment = new Comment();
        $comment->setUser($this->security->getUser());
        $comment->setArticle($article);
        $comment->setPostDate($time);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

                // return $this->redirectToRoute('comment_index', [], Response::HTTP_SEE_OTHER);
            return $this->redirect($request->getUri()); // Reload page after submit
        }

        return $this->renderForm('core/show.html.twig', [
            'comment' => $comment, 'form' => $form, 'article' => $article, 'comments' => $comments
        ]);

            // return $this->render('core/show.html.twig', ['article' => $article, 'comments' => $comments]);
    }

    /**
     * @Route("/account", name="user_account")
     */
    public function account(AuthenticationUtils $authenticationUtils, CommentRepository $commentRepository, UserRepository $userRepository): Response
    {
        if ($this->getUser()) {

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

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('core/index.html.twig', []);
    }
}
