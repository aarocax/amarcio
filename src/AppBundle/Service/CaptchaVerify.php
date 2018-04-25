<?php
 
namespace AppBundle\Service;
 

class CaptchaVerify
{
	protected $secret_key;
	protected $url;
 
  public function __construct($secret_key, $url)
  {
    $this->secret_key = $secret_key;
    $this->url = $url;
  }

  public function verify($recaptcha)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array( "secret" => $this->secret_key, "response" => $recaptcha ));
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response);
    return $data->success;
  }
}