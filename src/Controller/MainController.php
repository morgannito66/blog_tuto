<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Article;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @Route("/page/{page}", name="indexpagination")
     */
    public function index($page = 1, PaginatorInterface $paginator, ObjectManager $manager)
    {
        //3ARTICLES MAINS PAGES
        $repoArticle = $this->getDoctrine()->getRepository(Article::class);
        $mainArticle1 = $repoArticle->find(16);
        $mainArticle2 = $repoArticle->find(17);
        $mainArticle3 = $repoArticle->find(19);

        //ARTICLES
        $query = $manager->createQuery(
            'SELECT a
            FROM App\Entity\Article a
            ORDER BY a.id DESC'
        );
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $page /*page number*/,
            8 /*limit per page*/
        );

        return $this->render('main/index.html.twig', [
            'controller_name' => 'Accueil',
            'pagination' => $pagination,
            'mainArticle1' => $mainArticle1,
            'mainArticle2' => $mainArticle2,
            'mainArticle3' => $mainArticle3,
        ]);
    }

    /**
     * @Route("/a-propos", name="about")
     */
    public function about()
    {
        return $this->render('main/about.html.twig', [
            'controller_name' => 'À propos',
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('main/contact.html.twig', [
            'controller_name' => 'Contactez-nous',
        ]);
    }

    /**
     * @Route("/rechercher/{word}", name="rechercher")
     */
    public function rechercher($word = null, ObjectManager $manager)
    {
      $conn = $manager->getConnection();
      $sql = '
          SELECT * FROM article a
          WHERE a.title LIKE :word
          ORDER BY a.id DESC
          ';
      $stmt = $conn->prepare($sql);
      $stmt->execute(['word' => '%'.$word.'%']);
      if($stmt->rowCount() != 0){
        $articles = $stmt->fetchAll();

        $listArticles = array();
        foreach ($articles as $article) {
          $repoArticle = $this->getDoctrine()->getRepository(Article::class);
          $EntityArticle = $repoArticle->find($article['id']);
          $listArticles[] = $EntityArticle;
        }

        return $this->render('main/recherche.html.twig', [
            'controller_name' => 'Recherche : '.$word,
            'articles' => $listArticles,
        ]);
      } else {
        return $this->render('main/recherche.html.twig', [
            'controller_name' => 'Aucun résultat à votre recherche',
        ]);
      }
    }

}
