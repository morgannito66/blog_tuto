<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function category()
    {
        return $this->render('category/category.html.twig', [
            'controller_name' => 'Catégorie',
        ]);
    }
}
