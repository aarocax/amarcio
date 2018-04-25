<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Member;
use AppBundle\Service\PersistManager;

class MemberController extends Controller
{
  /**
   * @Route("/hazte-socio", name="hazte-socio")
   */
  public function indexAction(Request $request)
  {
    $member = new Member('socio');
    $form = $this->createForm('AppBundle\Form\MemberType', $member);
    $captcha_message = "";
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $captchaverify = $this->get('app.captcha.verify');
      //if (!$captchaverify->verify($request->get('g-recaptcha-response'))) {
      if ("a" === "b") {
        $captcha_message = "Se requiere un captcha válido";
      } else {
        $data = $form->getData();
        $data->setOrderId(strval(time()));
        (!$data->getCorrespondence()) ? $data->setCorrespondence(0) : null ; // put 0 if correspondence == false
        $data->setPaymentMedia('domiciliación');
        $pm = new PersistManager('member');
        $pm->persist($data);
        $this->sendEmail($data->getName(), $data->getSurname(), $data->getEmail());
        return $this->render('amarcio/thanks.html.twig', array(
          'message' => 'Muchas gracias por hacerte socio',
        ));
      }
    }

    return $this->render('amarcio/member.html.twig', [
      'form' => $form->createView(),
      'captcha_message' => $captcha_message,
    ]);
  }

  public function sendEmail($name, $surname, $email)
  {
    // realizamos el envío
    $emailer = $this->get('app.notification.emailer');
    $view = $this->renderView(
        'amarcio/notification-member.txt.twig',
        array('name' => $name, 'surname' => $surname)
        );
    $emailer->send($view, $email);
  }
}
