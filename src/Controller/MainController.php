<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\GC\UserBundle\TokenConfirm;
use App\Entity\Article;
use App\Entity\Newsletter;

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
        $mainArticle1 = $repoArticle->find(2);
        $mainArticle2 = $repoArticle->find(3);
        $mainArticle3 = $repoArticle->find(4);

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
     * @Route("/newsletter/{email}", name="newsletter")
     */
    public function newsletter($email = null, Request $request, ObjectManager $manager)
    {
        //IF AJAX
        if($request->isXmlHttpRequest()){
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                //VERIF SI MAIL EXISTE DEJA
                $emailFind = $this->getDoctrine()->getRepository(Newsletter::class)->findOneBy(['email' => $email]);
                if($emailFind == null){
                    //Token Confirm
                    $tokenClass = new TokenConfirm();
                    $token = $tokenClass->generateTokenConfirm();
                    // CREATE NEWSLETTER
                    $newsletter = new Newsletter;
                    $newsletter->setEmail($email)
                               ->setEnabled(0)
                               ->setToken($token);
                    $manager->persist($newsletter);
                    $manager->flush();

                     //sendMail normal
                     $linkConfirm = "https://www.titocode.fr/activateNewsletter/".$token;
                     // Plusieurs destinataires
                     $to  = $mail; // notez la virgule
                     // Sujet
                     $subject = 'Confirmez votre adresse email ! - TitoCode';
                     // message
                     $message = $this->renderView('emails/confirmToken.html.twig',array('linkConfirm' => $linkConfirm));
                     // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
                     $headers[] = 'MIME-Version: 1.0';
                     $headers[] = 'Content-type: text/html; charset=iso-8859-1';
                     // En-têtes additionnels
                     $headers[] = 'To: Invité <'.$mail.'>';
                     $headers[] = 'From: TitoCode <gregory@cascales.fr>';
                     // Envoi
                     mail($to, $subject, $message, implode("\r\n", $headers));

                    return new JsonResponse("1");
                } else {
                  return new JsonResponse("Email déjà utilisé !");
                }
            } else {
                return new JsonResponse("Email invalide");
            }
        }

        return $this->render('main/error_404.html.twig', [
            'controller_name' => 'Page introuvable',
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

    /**
     * @Route("/contact/{name}/{email}/{message}/{societe}", name="contact-send")
     */
    public function contactsend($name = null, $email = null, $message = null, $societe = null, Request $request)
    {

      //IF AJAX
      if($request->isXmlHttpRequest()){
        if(!empty($name) AND !empty($email) AND !empty($message)){
          if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //Get ip + infos
            if($request->getClientIp() != '127.0.0.1'){
              $details = json_decode(file_get_contents("http://ipinfo.io/".$request->getClientIp()."/json"));
            } else {
              $details = null;
            }
            //send mail
            $to  = $email; // notez la virgule
            // Sujet
            $subject = 'Nouveau message d\'un visiteur ! - TitoCode';
            // message
            if($details != null){
              $message = $this->renderView('emails/contact.html.twig',array('from' => $email, 'name' => $name, 'message' => $message, 'societe' => $societe, 'details' => $details));
            } else {
              $message = $this->renderView('emails/contact.html.twig',array('from' => $email, 'name' => $name, 'message' => $message, 'societe' => $societe));
            }

            // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=iso-8859-1';
            // En-têtes additionnels
            $headers[] = 'To: TitoCode <gregory@cascales.fr>';
            $headers[] = 'From: Invité <'.$email.'>';
            // Envoi
            if(mail($to, $subject, $message, implode("\r\n", $headers))){
                return new JsonResponse('1');
            } else {
                return new JsonResponse('Problème lors de l\'envoi du mail, si le problème persiste réessayez ultérieurement.');
            }
          } else {
              return new JsonResponse('L\'email renseigné n\'est pas valide.');
          }
        } else {
            return new JsonResponse('Le nom, email et message sont obligatoire !');
        }
      }


        return $this->render('main/error_404.html.twig', [
            'controller_name' => 'Page introuvable',
        ]);
    }

}
