<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Article;
use App\Entity\Commande;
use App\Entity\PaymentCommande;
use Stripe\Stripe;
use Stripe\Charge;
use App\GC\CommandeBundle\UniqRef;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/article-standard/{id}", name="article-standard")
     */
    public function article_standard(Article $article = null, ObjectManager $manager, Request $request)
    {
        // ARTICLE
        if($article != null){
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

          // IF PAYMENT STRIPE
          $responsePayment = null;
          if(!empty($request->request->get('stripeToken'))){
              Stripe::setApiKey("sk_test_NbH2VioANbwAM7RwxmrzFA5B");
              $token = $request->request->get('stripeToken');
              $charge = Charge::create([
                  'amount' => 299,
                  'currency' => 'eur',
                  'description' => "Achat du code source lié au tutoriel #".$article->getId()." pour 2,99€",
                  'source' => $token,
              ]);
              $charge = $charge->getLastResponse();
              $charge = (array)$charge; //Convert array pour le foreach
              $chargeInfos = $charge['json'];

              // Create Payment
              $newPayment = new PaymentCommande();
              $newPayment->setPaymentId($chargeInfos['id'])
                         ->setStatus($chargeInfos['status'])
                         ->setObject($chargeInfos['object'])
                         ->setAmount($chargeInfos['amount'])
                         ->setcreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', $chargeInfos['created'])))
                         ->setCurrency($chargeInfos['currency'])
                         ->setDescription($chargeInfos['description'])
                         ->setCardId($chargeInfos['source']['id'])
                         ->setCardBrand($chargeInfos['source']['brand'])
                         ->setCardCountry($chargeInfos['source']['country'])
                         ->setCardExpMonth($chargeInfos['source']['exp_month'])
                         ->setCardExpYear($chargeInfos['source']['exp_year'])
                         ->setCardLast4($chargeInfos['source']['last4'])
                         ->setEmail($chargeInfos['source']['name']);
              $manager->persist($newPayment);

              // Create Commande
              $ref = new UniqRef();
              $ref = $ref->generateRef();
              $newCommande = new Commande();
              $newCommande->setRef($ref)
                          ->setStatus("created")
                          ->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')))
                          ->setIpClient($request->getClientIp())
                          ->setArticle($article)
                          ->setPaymentCommande($newPayment);
              $manager->persist($newCommande);

               //ENVOI MAIL
               $to  = $newPayment->getEmail();
               // Sujet
               $subject = 'Titocode.fr - Merci pour votre achat !';
               // message
               $message = '
               <html>
                <body>
                 <h2>Merci pour votre achat sur Titocode.fr !</h2>
                 <p>Vous pouvez désormais télécharger le code source que vous avez commandé en <a href="https://www.titocode.fr/commande-source/'.$newCommande->getRef().'">CLIQUANT ICI</a>.<br>
                 Si une erreur intervient pendant votre téléchargement ou autres, veuillez nous contacter depuis notre <a href="https://www.titocode.fr/contact">formulaire de contact</a>.</p>
                 <hr>
                 <p>L\'équipe Titocode.</p>
                </body>
               </html>
               ';
               // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
               $headers[] = 'MIME-Version: 1.0';
               $headers[] = 'Content-type: text/html; charset=iso-8859-1';
               // En-têtes additionnels
               $headers[] = 'To: '.$newPayment->getEmail().' <'.$newPayment->getEmail().'>';
               $headers[] = 'From: Titocode.fr <contact@titocode.fr>';
               // Envoi
               mail($to, $subject, $message, implode("\r\n", $headers));

              //LIVRED
              $newCommande->setStatus("Livré");
              $manager->persist($newCommande);
              $manager->flush();

              $responsePayment['response'] = 1;
              $responsePayment['email'] = $newPayment->getEmail();
              $responsePayment['ref'] = $newCommande->getRef();
          }

          return $this->render('articles/article-standard.html.twig', [
              'controller_name' => $article->getTitle(),
              'article' => $article,
              'recentArticle' => $recentArticle,
              'articleCat' => $articleCat,
              'nameCategory' => $article->getCategory()->getName(),
              "responsePayment" => $responsePayment,
          ]);
        } else {
          return $this->render('main/error_404.html.twig', [
              'controller_name' => 'Page introuvable',
          ]);
        }
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
