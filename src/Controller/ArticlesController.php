<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/article-standard/{id}", name="article-standard")
     */
    public function article_standard(Article $article, ObjectManager $manager)
    {
        // AJOUTER +1 aux vues
        $article->add1Vue();
        $manager->persist($article);
        $manager->flush();

        //Article récent
        $repoArticle = $this->getDoctrine()->getRepository(Article::class);
        $recentArticle = $repoArticle->findOneBy(
            array(),
            array('id' => 'desc')
        );

        //Article récent hasard de la meme catégorie
        $articlesCat = $repoArticle->createQueryBuilder('c')
                                      ->where('c.category = :category')
                                      ->setParameter('category', $article->getCategory())
                                      ->orderBy('RAND()')
                                      ->setMaxResults(1)
                                      ->getQuery()
                                      ->execute();
        $articleCat = $articlesCat[0];


        return $this->render('articles/article-standard.html.twig', [
            'controller_name' => $article->getTitle(),
            'article' => $article,
            'recentArticle' => $recentArticle,
            'articleCat' => $articleCat,
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
