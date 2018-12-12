<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\GC\CaptchaBundle\captcha\ReCaptcha\ReCaptcha;
use App\GC\CaptchaBundle\captcha\ReCaptcha\RequestMethod\CurlPost;
use Symfony\Component\HttpFoundation\Request;

class TestController extends AbstractController
{
    /**
     * @Route("/reCaptcha", name="reCaptcha")
     */
    public function reCaptcha(Request $request)
    {
        // https://www.google.com/recaptcha/admin
        if($request->request->count() > 0){
          $recaptcha = new ReCaptcha('6LeCQYAUAAAAAMjYbCHNW0y3ZNg7doC_ZZ6-oj0_', new CurlPost);
          $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'));
          dump($resp->isSuccess());
          dump($resp->getErrorCodes());

        }


        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
