<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\GC\CaptchaBundle\captcha\ReCaptcha\ReCaptcha;
use App\GC\CaptchaBundle\captcha\ReCaptcha\RequestMethod\CurlPost;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('main/contact.html.twig', [
            'controller_name' => 'Contactez-nous',
            'recaptcha_publicKey' => $this->getParameter('RecaptchaBundle.publicKey'),
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
        $captcha =  $request->request->get('captcha');
        //Traitement
        if(!empty($name) AND !empty($email) AND !empty($message) AND !empty($captcha)){ //CONTACT
          if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $recaptcha = new ReCaptcha($this->getParameter('RecaptchaBundle.privateKey'), new CurlPost);
            $resp = $recaptcha->verify($captcha);
            if($resp->isSuccess()){
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

              // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
              $headers[] = 'MIME-Version: 1.0';
              $headers[] = 'Content-type: text/html; charset=iso-8859-1';
              // En-têtes additionnels
              $headers[] = 'To: TitoCode <gregory@cascales.fr>';
              $headers[] = 'From: TitoCode <gregory@cascales.fr>';
              // Envoi
              if(mail($to, $subject, $message, implode("\r\n", $headers))){
                  return new JsonResponse('1');
              } else {
                  return new JsonResponse('Problème lors de l\'envoi du mail, si le problème persiste réessayez ultérieurement.');
              }
            } else {
                return new JsonResponse('Problème technique avec le Captcha, réessayez ultérieurement !');
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

              // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
              $headers[] = 'MIME-Version: 1.0';
              $headers[] = 'Content-type: text/html; charset=iso-8859-1';
              // En-têtes additionnels
              $headers[] = 'To: TitoCode <gregory@cascales.fr>';
              $headers[] = 'From: TitoCode <gregory@cascales.fr>';
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
            return new JsonResponse('Remplissez tous les champs obligatoire !');
        }
      }


        return $this->render('main/error_404.html.twig', [
            'controller_name' => 'Page introuvable',
        ]);
    }
}
