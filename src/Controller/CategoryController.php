<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{id}", name="category")
     */
    public function category(Category $category)
    {
      $articles = $category->getArticles();

        return $this->render('category/category.html.twig', [
            'controller_name' => $category->getName(),
            'category' => $category,
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/getCategorys", name="getCategorys")
     */
    public function getCategorys(Request $request)
    {
        //IF AJAX GET PANIER
        if($request->isXmlHttpRequest()){
            $categorys = $this->getDoctrine()
                         ->getRepository(Category::class)
                         ->findAll();

             $arrayCategorys = array();
             foreach ($categorys as $key => $value) {
                 $id = $value->getId();
                 $name = $value->getName();

                 $arrayCategorys[] = array('id' => $id, 'name'=>$name);
             }

            $response = new JsonResponse($arrayCategorys);
            return $response;
        }

        return $this->render('main/error_404.html.twig', [
            'controller_name' => 'Page introuvable',
        ]);
    }
}
