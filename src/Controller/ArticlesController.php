<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            'nameCategory' => $article->getCategory()->getName(),
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

    /**
     * @Route("/getArticlesPop", name="getArticlesPop")
     */
    public function getArticlesPop(Request $request)
    {
        //IF AJAX GET ARTICLE POP
        if($request->isXmlHttpRequest()){
            $articlesPop = $this->getDoctrine()
                         ->getRepository(Article::class)
                         ->createQueryBuilder('a')
                         ->orderBy('a.vue', 'DESC')
                         ->setMaxResults(6)
                         ->getQuery()
                         ->execute();

             $arrayArticles = array();
             foreach ($articlesPop as $key => $value) {
                 $id = $value->getId();
                 $title = $value->getTitle();
                 $createdAt = $value->getCreatedAt()->format('d/m/Y');;
                 $publicThumb = $value->getPublicThumb();
                 $vue = $value->getVue();
                 $arrayArticles[] = array('id' => $id, 'title'=>$title, 'createdAt'=>$createdAt, 'publicThumb' => $publicThumb, 'vue' => $vue);
             }

            $response = new JsonResponse($arrayArticles);
            return $response;
        }

        return $this->render('main/error_404.html.twig', [
            'controller_name' => 'Page introuvable',
        ]);
    }
}
