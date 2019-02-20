<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\GC\CaptchaBundle\captcha\ReCaptcha\ReCaptcha;
use App\GC\CaptchaBundle\captcha\ReCaptcha\RequestMethod\CurlPost;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\GC\MailBundle\Mail as GCMailer;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('main/contact.html.twig', [
            'controller_name' => 'Contactez-nous',
            'recaptcha_publicKey' => $this->getParameter('RecaptchaBundle')['publicKey'],
        ]);
    }

    /**
     * @Route("/contact/sendContact", name="contact-send")
     * @Route("/feedback/{Femail}/{Fmessage}", name="feedback-send")
     */
    public function contactsend($Femail = null, $Fmessage = null, Request $request)
    {
      //IF AJAX
      if($request->isXmlHttpRequest()){
        //Attributs
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $message = $request->request->get('message');
        $societe = $request->request->get('societe');
        //Traitement
        if(!empty($name) AND !empty($email) AND !empty($message)){ //CONTACT
          if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //Get ip + infos
            if($request->getClientIp() != '127.0.0.1'){
              $details = json_decode(file_get_contents("http://ipinfo.io/".$request->getClientIp()."/json"));
            } else {
              $details = null;
            }
            //send mail
            $to  = 'gregory.cascales@gmail.com'; // notez la virgule
            // Sujet
            $subject = 'Nouveau message d\'un visiteur ! - TitoCode';
            // message
            if($details != null){
              $message = $this->renderView('emails/contact.html.twig',array('from' => $email, 'name' => $name, 'message' => $message, 'societe' => $societe, 'details' => $details));
            } else {
              $message = $this->renderView('emails/contact.html.twig',array('from' => $email, 'name' => $name, 'message' => $message, 'societe' => $societe));
            }

            //PHP MAILER
            $mail = new GCMailer();
            if($mail->sendEmail($subject, $message, $to)){
                return new JsonResponse('1');
            } else {
                return new JsonResponse('Problème lors de l\'envoi du mail, si le problème persiste réessayez ultérieurement.');
            }
          } else {
              return new JsonResponse('L\'email renseigné n\'est pas valide.');
          }
        } else if(!empty($Femail) AND !empty($Fmessage)){ //FEEDBACK
            if (filter_var($Femail, FILTER_VALIDATE_EMAIL)) {
              //Get ip + infos
              if($request->getClientIp() != '127.0.0.1' AND $request->getClientIp() != '::1'){
                $details = json_decode(file_get_contents("http://ipinfo.io/".$request->getClientIp()."/json"));
              } else {
                $details = null;
              }
              //send mail
              $to  = 'gregory.cascales@gmail.com'; // notez la virgule
              // Sujet
              $subject = 'Nouveau feedback d\'un visiteur ! - TitoCode';
              // message
              if($details != null){
                $message = $this->renderView('emails/contact.html.twig',array('from' => $Femail, 'name' => "FEEDBACK", 'message' => $Fmessage, 'societe' => "", 'details' => $details));
              } else {
                $message = $this->renderView('emails/contact.html.twig',array('from' => $Femail, 'name' => "FEEDBACK", 'message' => $Fmessage, 'societe' => ""));
              }

              //PHP MAILER
              $mail = new GCMailer();
              if($mail->sendEmail($subject, $message, $to)){
                  return new JsonResponse('1');
              } else {
                  return new JsonResponse('Problème lors de l\'envoi du mail, si le problème persiste réessayez ultérieurement.');
              }
            } else {
                return new JsonResponse('L\'email renseigné n\'est pas valide.');
            }
        } else {
            return new JsonResponse('Remplissez tous les champs obligatoire !');
        }
      }


        return $this->render('main/error_404.html.twig', [
            'controller_name' => 'Page introuvable',
        ]);
    }
}
