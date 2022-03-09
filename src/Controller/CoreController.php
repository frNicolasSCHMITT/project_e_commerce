<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Entity\Comment;
use App\Entity\Article;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;


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
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy(
            ['available' => true],
            ['id' => 'ASC'],
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
}
