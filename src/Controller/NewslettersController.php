<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\GC\CryptBundle\Crypt;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Newsletter;
use App\GC\UserBundle\TokenConfirm;
use App\GC\MailBundle\Mail as GCMailer;

class NewslettersController extends AbstractController
{
    /**
     * @Route("/unfollownewsletters/{email}/{confirm}", name="unfollow-newsletters")
     */
    public function unfollow($email = null, $confirm = null, Request $request, ObjectManager $manager)
    {
        if($email != null){
          if($confirm == null){ //SI L'USER EST VENU SIMPLEMENT SE DESINSCRIRE
            return $this->render('newsletters/unfollow-newsletters.html.twig', [
                'controller_name' => 'Désinscription aux newsletters',
            ]);
          } else { //SI ON A UN CONFIRM
            if($request->isXmlHttpRequest()){ //SI C'EST BIEN UNE REQUETE AJAX
              $Crypt = new Crypt();
              // $eCrypt = $Crypt->dec_enc('encrypt', 'titocode');
              $eDecrypt = $Crypt->dec_enc('decrypt', $email);
              if($eDecrypt == $confirm){
                $repository = $this->getDoctrine()->getRepository(Newsletter::class);
                $emailNewsletters = $repository->findOneBy([
                    'email' => $confirm,
                ]);
                if($emailNewsletters != null){
                  $manager->remove($emailNewsletters);
                  $manager->flush();
                  return new JsonResponse(1);
                } else {
                  return new JsonResponse("L'email est inexistante dans notre liste d'inscrit aux newsletters");
                }
              } else {
                return new JsonResponse("L'email ne correspond pas au lien qui vous a été envoyé par mail !");
              }
            } else {
              return $this->render('main/error_404.html.twig', [
                  'controller_name' => 'Page introuvable',
              ]);
            }
          }
        } else {
          return $this->render('main/error_404.html.twig', [
              'controller_name' => 'Page introuvable',
          ]);
        }
    }

    /**
     * @Route("/newsletter/{email}", name="newsletter")
     * @Route("/newsletter/{email}/{token}", name="newsletter_token")
     */
    public function newsletter($email = null, $token = null, Request $request, ObjectManager $manager)
    {
        //IF AJAX ADD NEWSLETTER
        if($request->isXmlHttpRequest()){

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                //VERIF SI MAIL EXISTE DEJA
                $emailFind = $this->getDoctrine()->getRepository(Newsletter::class)->findOneBy(['email' => $email]);
                if($emailFind == null){

                  //Token Confirm
                  $tokenClass = new TokenConfirm();
                  $token = $tokenClass->generateTokenConfirm();

                  //send mail
                  $to  = $email; // notez la virgule
                  // Sujet
                  $subject = 'Confirmez votre email pour vous inscrire aux newsletters ! - TitoCode';
                  //unfollow
                  $Crypt = new Crypt();
                  $eCrypt = $Crypt->dec_enc('encrypt', $email);
                  // message
                  $message = $this->renderView('emails/confirmToken.html.twig',array('token' => $token, 'email' => $email, 'eCrypt' => $eCrypt,));

                  //PHP MAILER
                  $mail = new GCMailer();
                  if($mail->sendEmail($subject, $message, $to)){
                     // CREATE NEWSLETTER
                     $newsletter = new Newsletter;
                     $newsletter->setEmail($email)
                                ->setEnabled(0)
                                ->setToken($token);
                     $manager->persist($newsletter);
                     $manager->flush();

                      return new JsonResponse("1");
                   } else {
                      return new JsonResponse("Problème lors du traitement, veuillez contacter titocode@cascales.fr");
                   }

                } else {
                  return new JsonResponse("Email déjà utilisé !");
                }
            } else {
                return new JsonResponse("Email invalide");
            }
        }

        //IF CONFIRM TOKEN
        if($token != null){
          $repository = $this->getDoctrine()->getRepository(Newsletter::class);
          $theNewsletter = $repository->findOneBy([
              'email' => $email,
              'token' => $token,
          ]);
          if($theNewsletter != null){
            $theNewsletter->setToken('')
                          ->setEnabled(1);
            $manager->persist($theNewsletter);
            $manager->flush();

            $this->addFlash('success', 'Vous êtes désormais inscrit à notre newsletter, merci de votre confiance !');
            return $this->redirectToRoute('index');
          } else {
            $this->addFlash('warning', 'Le token est incorrect ou épuisé !');
            return $this->redirectToRoute('index');
          }
        }

        return $this->render('main/error_404.html.twig', [
            'controller_name' => 'Page introuvable',
        ]);
    }

}
