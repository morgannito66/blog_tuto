<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Entity\Category;
use App\GC\AdminBundle\CodeInsertHTML;

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
          if(!empty($request->request->get('title')) AND !empty($request->request->get('content')) AND !empty($request->files->get('privateThumb')) AND !empty($request->files->get('publicThumb')) AND !empty($request->request->get('category'))){
            // UPLOAD IMAGE PRIVATETHUMB
            $privateThumb = $request->files->get('privateThumb');
            $privateThumbName = $this->generateUniqueFileName().'.'.$privateThumb->guessExtension();
            try {
                $privateThumb->move(
                    $this->getParameter('kernel.project_dir').'/assets/img/articles/private/',
                    $privateThumbName
                );
            } catch (FileException $e) {
                $error = $e;
            }

            // UPLOAD IMAGE PUBLIC
            $publicThumb = $request->files->get('publicThumb');
            $publicThumbName = $this->generateUniqueFileName().'.'.$publicThumb->guessExtension();
            try {
                $publicThumb->move(
                    $this->getParameter('kernel.project_dir').'/assets/img/articles/public/',
                    $publicThumbName
                );
            } catch (FileException $e) {
                $error = $e;
            }

            if(empty($error)){
              $idCategory = $request->request->get('category');
              $repo = $this->getDoctrine()->getRepository(Category::class);
              $category = $repo->find($idCategory);
              if($category != null){
                $tagsString = $request->request->get('tags');
                if($tagsString != ''){
                  $tags = explode(',', $tagsString);
                } else {
                  $tags = null;
                }

                // convert if have <code> html in content
                $convertHTML = new CodeInsertHTML();
                $content = $convertHTML->generateCodeHTML($request->request->get('content'));

                $article = new Article();
                $article->setTitle($request->request->get('title'));
                $article->setContent($content);
                $article->setTags($tags);
                $article->setPrivateThumb('assets/img/articles/private/'.$privateThumbName);
                $article->setPublicThumb('assets/img/articles/public/'.$publicThumbName);
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

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
