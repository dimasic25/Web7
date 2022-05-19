<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\NewsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class NewsController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    #[Route('/', name: 'app_news')]
    public function index(NewsRepository $repository): Response
    {
        $news = $repository->findAll();

        return $this->render('news/index.html.twig', [
            'news_list' => $news,
        ]);
    }

    #[Route('/news/{id}', name: 'news_details', methods: ['GET', 'POST'])]
    public function showDetails(int $id, NewsRepository $repository, CommentRepository $commentRepository, Security $security, Request $request): Response
    {
        $user = $security->getUser();

        $news = $repository->findOneBy(['id' => $id]);
        if ($news === null) {
            return $this->redirectToRoute('app_news');
        }

        $form = $this->createForm(CommentFormType::class);
        $form->handleRequest($request);
        $comments = $commentRepository->findActiveByNews($news);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentData = $form->get('body')->getData();

            $comment = new Comment();
            $comment->setOwner($user);
            $comment->setNews($news);
            $comment->setBody($commentData);

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('news_details', [
                'id' => $news->getId(),
                'comments' => $comments
            ]);
        }

        if ($news->getOwner() !== $user) {
            $news->incrementViewCount();
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($news);
            $entityManager->flush();
        }

        return $this->render('news/news-details.html.twig', [
            'news' => $news,
            'comments' => $comments,
            'comment_form' => $form->createView(),
        ]);
    }
}