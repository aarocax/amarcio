<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Donation;
use AppBundle\Entity\Member;
use AppBundle\Service\PersistManager;
use AppBundle\Service\Utils;


class DonationController extends Controller
{
  /**
   * @Route("/haz-un-donativo", name="haz-un-donativo")
   */
  public function indexAction(Request $request)
  {

  	$member = new Member('donante');
    $form = $this->createForm('AppBundle\Form\DonationType', $member);

    $captcha_message = "";

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
    	$captchaverify = $this->get('app.captcha.verify');
      //if (!$captchaverify->verify($request->get('g-recaptcha-response'))) {
    	if ("a" === "b" ) {
    		$captcha_message = "Se requiere un captcha válido";
    	} else {
        $orderId = strval(time());
    		$memberData = $form->getData();
        $memberData->setOrderId($orderId);
        $contribution = ($memberData->getContribution() > 0 ) ? $memberData->getContribution() : $form->get("otro")->getData();
        $memberData->setContribution($contribution);
        (!$memberData->getCorrespondence()) ? $memberData->setCorrespondence(0) : null ; // put 0 if correspondence == false
        

        switch ($memberData->getPaymentMedia()) {
        	case 'paypal':
        		$paypal = $this->get('app.paypaypal');
        		$payment = $paypal->payment($contribution);
            $transactions = $payment->getTransactions();
            $transaction = $transactions[0];
            $memberData->setOrderId($transaction->invoice_number);
        		$approvalUrl = $payment->getApprovalLink();

            $pm = new PersistManager('member');
            $pm->persist($memberData); 
            
          	$redirect = $this->redirect($payment->getApprovalLink());
          	return $redirect;
        		
        		break;
        	case 'card':
        		$redsys = $this->get('app.payredsys');
        		$redsys->payment($contribution*100);
            $pm = new PersistManager('member');
            $pm->persist($memberData); 
        		return $this->render('amarcio/redsys-send-pay-form.html.twig', array(
		          'version' => $redsys->encryptedType(),
		          'params' => $redsys->getParams(),
		          'signature' => $redsys->getSignature(),
		          'comerce_url' => $redsys->getComerceUrl(),
		          'contribution' => Utils::formatAmount($contribution),
		        ));
        		break;
        	default:
        		break;
        }
    	}

    }

    return $this->render('amarcio/donation.html.twig', [
      'form' => $form->createView(),
      'captcha_message' => $captcha_message,
    ]);
  }

  /**
   * @Route("/paypal-response-ok/", name="paypal-response-ok")
   */
  public function paypalResponseOkAction(Request $request)
  {
  	$paymentId = $request->query->get('paymentId');

    if ($paymentId) {
      $pay = $this->get('app.paypaypal');
      $data = $pay->getResponse($paymentId);

      if ($data) {
        $transactions = $data->getTransactions();
        $transaction = $transactions[0];

        $pm = new PersistManager('paypal-response');

        // Buscamos si ya se ha registrado la operación, por si el usuario reenvía la página de respuesta
        $donation = $pm->find(__DIR__.'/../../../var/data/donation.csv', $transaction->invoice_number);

        if (!$donation) {
          // Buscamos en el fichero member
          $member = $pm->find(__DIR__.'/../../../var/data/member.csv', $transaction->invoice_number);
          $date = new \DateTime($data->create_time);
          
          $donation = new Donation();
          $donation->setOrderId($transaction->invoice_number);
          $donation->setMember($member);
          $donation->setState($data->state);
          $donation->setMedia("paypal");
          $donation->setDate($date->format('d-m-Y H:i:s'));
          $donation->setAmount($transaction->amount->total);
          $donation->setReference($data->id);
          
          $pm->persist($donation);

          $message = "Muchas gracias por hacer su donativo";
        } else {
          $message = "Su donativo ya fué registrado. Muchas gracias por hacer su donativo";
        }
      } else {
        $message = "No se ha podido verificar la operación";
      }
    } else {
      die();
    }
    	
  	return $this->render('amarcio/thanks.html.twig', array(
        'message' => $message,
      ));
  }

  /**
   * @Route("/paypal-cancel/", name="paypal-cancel")
   */
  public function paypalCancelAction(Request $request)
  {
    $paymentId = $request->query->get('paymentId');
  	$pay = $this->get('app.paypaypal');
  	$response = $pay->getResponse($paymentId);
  	$pm = new PersistManager('paypal-response');
    $pm->persist($response);
    return $this->render('default/thanks.html.twig', array(
        'message' => 'A cancelado la donación...',
      ));
  }

  /**
   * @Route("/paypal-error/", name="paypal-error")
   */
  public function paypalErrorAction(Request $request)
  {
    return $this->render('default/thanks.html.twig', array(
        'message' => 'Error al procesar el pago por paypal...',
      ));
  }

  /**
   * @Route("/tpv-response/", name="tpv-response")
   */
  public function tpvResponseAction(Request $request)
  {

    $redsys = $this->get('app.payredsys');

    if (!empty( $_POST ) ) {//URL DE RESP. ONLINE
          
        $version = $_POST["Ds_SignatureVersion"];
        $data = $_POST["Ds_MerchantParameters"];
        $signatureRecibida = $_POST["Ds_Signature"];
          
        $decodec = $redsys->decodeMerchantParameters($data);
        $kc = $redsys->getEncryptedKey();
        $firma = $redsys->createMerchantSignatureNotif($kc,$data);
      
        if ($firma === $signatureRecibida){
          
          $data = json_decode(urldecode($decodec));

          $pm = new PersistManager('redsys-response');

          $donation = $pm->find(__DIR__.'/../../../var/data/donation.csv', $data->Ds_Order);

          if (!$donation) {
            // Buscamos en el fichero member
            $member = $pm->find(__DIR__.'/../../../var/data/member.csv', $data->Ds_Order);
            $donation = new Donation();
            $donation->setOrderId($data->Ds_Order);
            $donation->setMember($member);
            $donation->setState($data->Ds_Response);
            $donation->setMedia("redsys");
            $donation->setDate($data->Ds_Date." ".$data->Ds_Hour);
            $donation->setAmount($data->Ds_Amount / 100);
            $donation->setReference("refence");
            $pm->persist($donation);
            $this->sendEmail($member[3], $member[4], $member[6]);
            $message = "Muchas gracias por hacer su donativo";
          } else {
            $message = "Su donativo ya fué registrado. Muchas gracias por hacer su donativo";
          }
          
        } else {
          $message = "No se ha podido verificar la operación";
        }
    } else {
      if (!empty( $_GET ) ) {//URL DE RESP. ONLINE
          
        $version = $_GET["Ds_SignatureVersion"];
        $data = $_GET["Ds_MerchantParameters"];
        $signatureRecibida = $_GET["Ds_Signature"];
          
        $decodec = $redsys->decodeMerchantParameters($data);
        $kc = $redsys->getEncryptedKey();
        $firma = $redsys->createMerchantSignatureNotif($kc,$data);
      
        if ($firma === $signatureRecibida){
        	
          $data = json_decode(urldecode($decodec));

          $pm = new PersistManager('redsys-response');

          $donation = $pm->find(__DIR__.'/../../../var/data/donation.csv', $data->Ds_Order);


          if (!$donation) {
            // Buscamos en el fichero member
            $member = $pm->find(__DIR__.'/../../../var/data/member.csv', $data->Ds_Order);
            $donation = new Donation();
            $donation->setOrderId($data->Ds_Order);
            $donation->setMember($member);
            $donation->setState($data->Ds_Response);
            $donation->setMedia("redsys");
            $donation->setDate($data->Ds_Date." ".$data->Ds_Hour);
            $donation->setAmount($data->Ds_Amount / 100);
            $donation->setReference("refence");
      			$pm->persist($donation);
            $this->sendEmail($member[3], $member[4], $member[6]);
            $message = "Muchas gracias por hacer su donativo";
          } else {
            $message = "Su donativo ya fué registrado. Muchas gracias por hacer su donativo";
          }
          
        } else {
          $message = "No se ha podido verificar la operación";
        }
      }
      else{
        die();
      }
    }

    return $this->render('amarcio/thanks.html.twig', array(
        'message' => $message,
      ));
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
