<?php


namespace AppBundle\Service;

class PayPaypal {

	private $apiContext;
	private $OAuthTokenCredential;
	private $payer;
	private $amount;
	private $transaction;
	private $redirectUrls;
	private $payment;

	function __construct($api_client_id, $api_client_secret, $return_url, $cancel_url)
  {
    $this->OAuthTokenCredential = new \PayPal\Auth\OAuthTokenCredential($api_client_id, $api_client_secret) ;
    $this->apiContext = new \PayPal\Rest\ApiContext($this->OAuthTokenCredential);
    $this->apiContext->setConfig(
      array(
        'log.LogEnabled' => true,
        'log.FileName' => __DIR__.'/../../../var/logs/PayPal.log',
        'log.LogLevel' => 'DEBUG'
      )
		);
    $this->payer = new \PayPal\Api\Payer();
    $this->payer->setPaymentMethod('paypal');
    $this->amount = new \PayPal\Api\Amount();
    $this->transaction = new \PayPal\Api\Transaction();
    $this->redirectUrls = new \PayPal\Api\RedirectUrls();
    $this->redirectUrls->setReturnUrl($return_url);
    $this->redirectUrls->setCancelUrl($cancel_url);
    $this->payment = new \PayPal\Api\Payment();
  }

  public function payment($amount) {
  	$this->amount->setTotal($amount);
    $this->amount->setCurrency('EUR');
    $this->transaction->setAmount($this->amount)
        ->setDescription("Payment description")
        ->setInvoiceNumber(strval(time()));
    
    $this->payment->setIntent('sale')
      ->setPayer($this->payer)
      ->setTransactions(array($this->transaction))
      ->setRedirectUrls($this->redirectUrls);

    try {
    	$result = $this->payment->create($this->apiContext);
    } catch(\PayPal\Exception\PayPalConnectionException $ex) {
    	$result = false;
    }
    return $result;
  }

  public function getResponse($paymentId)
  {
    try {
      $result = $this->payment::get($paymentId, $this->apiContext);
    } catch(\PayPal\Exception\PayPalConnectionException $ex) {
      $result = false;
    }
    return $result;
  }
}