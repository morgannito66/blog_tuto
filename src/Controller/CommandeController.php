<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commande;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommandeController extends AbstractController
{
    /**
     * @Route("/commande-source/{ref}", name="commande-source")
     */
    public function comSource($ref = null)
    {
        if($ref != null){
          //Commande
          $repo = $this->getDoctrine()->getRepository(Commande::class);
          $commandeFound = $repo->createQueryBuilder('c')
                                        ->where('c.ref = :ref')
                                        ->setParameter('ref', $ref)
                                        ->setMaxResults(1)
                                        ->getQuery()
                                        ->getOneOrNullResult();

          if($commandeFound != null){
              if($commandeFound->getBeDownload() == 1){
                // ERROR Déjà téléharger
                $this->addFlash('warning', 'Le fichier dont vous essayez d\'avoir l\'accès a déjà été téléchargé, si vous pensez qu\'il s\'agit d\'une erreur veuillez nous contacter.');
                return $this->redirectToRoute('404');
              }
          } else {
            //ERROR commande inexistante
            $this->addFlash('warning', 'Cette commande est inexistante, si vous pensez qu\'il s\'agit d\'une erreur veuillez nous contacter.');
            return $this->redirectToRoute('404');
          }

        } else {
          // ERROR PAGE AUCUNE REF
          $this->addFlash('warning', 'Aucune référence n\'a été renseignée.');
          return $this->redirectToRoute('404');
        }

        //Afficher page pour download
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'Téléchargez le code source',
            'commande' => $commandeFound,
        ]);
    }

    /**
     * @Route("/commande-checked/{ref}", name="commande-checked")
     */
    public function comChecked($ref = null, Request $request, ObjectManager $manager)
    {
      //IF AJAX
      if($request->isXmlHttpRequest()){
          if($ref != null){
            //Commande
            $repo = $this->getDoctrine()->getRepository(Commande::class);
            $commandeFound = $repo->createQueryBuilder('c')
                                          ->where('c.ref = :ref')
                                          ->setParameter('ref', $ref)
                                          ->setMaxResults(1)
                                          ->getQuery()
                                          ->getOneOrNullResult();

            if($commandeFound != null){
                //CHECKED
                $commandeFound->setBeDownload(1)
                              ->setIpDownload($request->getClientIp());
                $manager->persist($commandeFound);
                $manager->flush();
                return new JsonResponse(1);
            } else {
              //ERROR commande inexistante
              return new JsonResponse("Commande inexistante.");
            }

          } else {
            // ERROR PAGE 404
            return new JsonResponse("Référence de la commande non-renseignée.");
          }
      }

      //Afficher page pour download
      return $this->render('main/error_404.html.twig', [
          'controller_name' => 'Page introuvable',
      ]);
    }
}
