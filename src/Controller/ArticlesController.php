<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/article-standard", name="article-standard")
     */
    public function article_standard()
    {
        return $this->render('articles/article-standard.html.twig', [
            'controller_name' => 'Article standard',
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
