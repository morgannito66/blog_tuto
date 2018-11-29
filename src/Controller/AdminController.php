<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Entity\Category;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'Administration',
        ]);
    }

    /**
     * @Route("/admin/addArticle", name="addArticle")
     */
    public function addArticle(Request $request, ObjectManager $manager)
    {
        $error = null;

        if($request->isMethod('POST')) {
          if(!empty($request->request->get('title')) AND !empty($request->request->get('content')) AND !empty($request->request->get('privateThumb')) AND !empty($request->request->get('publicThumb')) AND !empty($request->request->get('category'))){
            $idCategory = $request->request->get('category');
            $repo = $this->getDoctrine()->getRepository(Category::class);
            $category = $repo->find($idCategory);
            if($category != null){
              $tagsString = $request->request->get('tags');
              $tags = explode(',', $tagsString);

              $article = new Article();
              $article->setTitle($request->request->get('title'));
              $article->setContent($request->request->get('content'));
              $article->setTags($tags);
              $article->setPrivateThumb($request->request->get('privateThumb'));
              $article->setPublicThumb($request->request->get('publicThumb'));
              $article->setType('standard');
              $article->setVue(1);
              $article->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));
              $article->setCategory($category);
              $manager->persist($article);
              $manager->flush();
              return $this->redirectToRoute('index');
            } else {
              $error = "La catégorie renseignée est introuvable !";
            }
          } else {
            $error = "Le titre, contenu, image dans/hors article et la catégorie sont obligatoire !";
          }
        }

        //Récup des catégories
        $repo = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repo->findAll();

        return $this->render('admin/add-article.html.twig', [
            'controller_name' => 'Ajouter un article',
            'categories' => $categories,
            'error' => $error,
        ]);
    }
}
