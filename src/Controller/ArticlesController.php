<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/article-standard/{id}", name="article-standard")
     */
    public function article_standard(Article $article)
    {
        return $this->render('articles/article-standard.html.twig', [
            'controller_name' => $article->getTitle(),
            'article' => $article,
        ]);
    }

    /**
     * @Route("/article-video", name="article-video")
     */
    public function article_video()
    {
        return $this->render('articles/article-video.html.twig', [
            'controller_name' => 'Article vidéo',
        ]);
    }
}
