<?php


namespace AppBundle\Service;
use AppBundle\Service\RedsysAPI\RedsysAPI;

class PayRedsys extends RedsysAPI {

	private $merchant_merchantcode;
	private $terminal_number;
	private $encrypted_key;
	private $currency;
	private $transaction_type;
	private $encrypted_type;
	private $commerce_url;
	private $merchant_url;
	private $merchant_url_ok;
	private $merchant_url_ko;
	private $params;
	private $signature;

	function __construct($merchant_merchantcode, $terminal_number, $encrypted_key, $currency, $transaction_type, $encrypted_type, $commerce_url, $merchant_url, $merchant_url_ok, $merchant_url_ko)
  {
    $this->merchant_merchantcode = $merchant_merchantcode;
    $this->terminal_number = $terminal_number;
    $this->encrypted_key = $encrypted_key;
    $this->currency = $currency;
    $this->transaction_type = $transaction_type;
    $this->encrypted_type = $encrypted_type;
    $this->commerce_url = $commerce_url;
    $this->merchant_url = $merchant_url;
    $this->merchant_url_ok = $merchant_url_ok;
    $this->merchant_url_ko = $merchant_url_ko;
  }



  public function payment($amount)
  {
  	$id=time();
  	parent::setParameter("DS_MERCHANT_MERCHANTCODE", $this->merchant_merchantcode);
	  parent::setParameter("DS_MERCHANT_CURRENCY", $this->currency);
	  parent::setParameter("DS_MERCHANT_TRANSACTIONTYPE", $this->transaction_type);
	  parent::setParameter("DS_MERCHANT_TERMINAL", $this->terminal_number);
	  parent::setParameter("DS_MERCHANT_MERCHANTURL", $this->merchant_url);
	  parent::setParameter("DS_MERCHANT_URLOK", $this->merchant_url_ok);    
	  parent::setParameter("DS_MERCHANT_URLKO", $this->merchant_url_ko);
	  parent::setParameter("DS_MERCHANT_ORDER",strval($id));
	  parent::setParameter("DS_MERCHANT_AMOUNT",$amount);

  	$this->params = parent::createMerchantParameters();
  	$this->signature = parent::createMerchantSignature($this->encrypted_key);
  }

  public function getParams()
  {
  	return $this->params;
  }

  public function getSignature()
  {
  	return $this->signature;
  }

  public function encryptedType()
  {
  	return $this->encrypted_type;
  }

  public function getComerceUrl()
  {
  	return $this->commerce_url;
  }

  public function getEncryptedKey()
  {
    return $this->encrypted_key;
  }
}