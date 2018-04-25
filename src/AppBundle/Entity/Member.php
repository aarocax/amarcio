<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Member
{

  protected $orderId;

  protected $creationDate;

  protected $type;

  /**
    * @Assert\Length(
    *      min = 2,
    *      max = 50,
    *      minMessage = "El campo nombre debe tener al menos {{ limit }} caracteres",
    *      maxMessage = "El campo nombre no puede tener más de {{ limit }} caracteres"
    * )
    */
  protected $name;

  /**
    * @Assert\Length(
    *      min = 2,
    *      max = 50,
    *      minMessage = "El campo apellidos debe tener al menos {{ limit }} caracteres",
    *      maxMessage = "El campo apellidos no puede tener más de {{ limit }} caracteres"
    * )
    */
  protected $surname;

  /**
    * @Assert\NotBlank()
    */
  protected $nif;

  /**
    * @Assert\NotBlank()
    * @Assert\Email(
    *     message = "Por favor introduzca un email válido.",
    *     checkMX = true
    * )
    */
  protected $email;

  /**
    * @Assert\NotBlank()
    */
  protected $phone;

  /**
    * @Assert\NotBlank()
    * @Assert\Length(
    *      min = 5,
    *      minMessage = "El código postal debe contener 5 dígitos.",
    * )
    */
  protected $cp;

  protected $address;

  protected $town;

  protected $province;

  protected $iban;

  protected $paymentMedia;

  /**
    * @Assert\NotBlank()
    * )
    */
  protected $contribution;

  protected $frequency;

  protected $acceptConditions;

  protected $known;

  protected $correspondence;

  
  // ***********************************
  // methods
  // ***********************************

  public function __construct($type){
    $date = new \DateTime();
    $this->setCreationDate($date->format('d-m-Y H:i:s'));
    $this->setType($type);
  }

  public function getOrderId()
  {
    return $this->orderId;
  }

  public function setOrderId($orderId)
  {
    $this->orderId = $orderId;
  }

  public function getCreationDate()
  {
    return $this->creationDate;
  }

  public function setCreationDate($creationDate)
  {
    $this->creationDate = $creationDate;
  }

  public function getType()
  {
    return $this->type;
  }

  public function setType($type)
  {
    $this->type = $type;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setName($name)
  {
    $this->name = $name;
  }

  public function getSurname()
  {
    return $this->surname;
  }

  public function setSurname($surname)
  {
    $this->surname = $surname;
  }

  public function getNif()
  {
    return $this->nif;
  }

  public function setNif($nif)
  {
    $this->nif = $nif;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function setEmail($email)
  {
    $this->email = $email;
  }

  public function getPhone()
  {
    return $this->phone;
  }

  public function setPhone($phone)
  {
    $this->phone = $phone;
  }

  public function getCp()
  {
    return $this->cp;
  }

  public function setCp($cp)
  {
    $this->cp = $cp;
  }

  public function getAddress()
  {
    return $this->address;
  }

  public function setAddress($address)
  {
    $this->address = $address;
  }

  public function getTown()
  {
    return $this->town;
  }

  public function setTown($town)
  {
    $this->town = $town;
  }

  public function getProvince()
  {
    return $this->province;
  }

  public function setProvince($province)
  {
    $this->province = $province;
  }

  public function getIban()
  {
    return $this->iban;
  }

  public function setIban($iban)
  {
    $this->iban = $iban;
  }

  public function getPaymentMedia()
  {
      return $this->paymentMedia;
  }

  public function setPaymentMedia($paymentMedia)
  {
      $this->paymentMedia = $paymentMedia;
  }

  public function getContribution()
  {
    return $this->contribution;
  }

  public function setContribution($contribution)
  {
    $this->contribution = $contribution;
  }

  public function getFrequency()
  {
    return $this->frequency;
  }

  public function setFrequency($frequency)
  {
    $this->frequency = $frequency;
  }

  public function getAcceptConditions()
  {
    return $this->acceptConditions;
  }

  public function setAcceptConditions($acceptConditions)
  {
    $this->acceptConditions = $acceptConditions;
  }

  public function getKnown()
  {
    return $this->known;
  }

  public function setKnown($known)
  {
    $this->known = $known;
  }

  public function getCorrespondence()
  {
    return $this->correspondence;
  }

  public function setCorrespondence($correspondence)
  {
    $this->correspondence = $correspondence;
  }

}
