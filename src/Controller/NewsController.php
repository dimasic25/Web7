<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    #[Route('/', name: 'app_news')]
    public function index(NewsRepository $repository): Response
    {
        $news = $repository->findAll();

        return $this->render('news/index.html.twig', [
            'news_list' => $news,
        ]);
    }
}