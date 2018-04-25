<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Donation
{
	protected $orderId;
	protected $member;
	protected $state;
	protected $media;
	protected $date;
	protected $amount;
	protected $reference;

	public function getOrderId()
  {
    return $this->orderId;
  }

  public function setOrderId($orderId)
  {
    $this->orderId = $orderId;
  }

  public function getMember()
  {
    return $this->member;
  }

  public function setMember($member)
  {
    $this->member = $member;
  }

  public function getState()
  {
    return $this->state;
  }

  public function setState($state)
  {
    $this->state = $state;
  }

  public function getMedia()
  {
    return $this->media;
  }

  public function setMedia($media)
  {
    $this->media = $media;
  }

  public function getDate()
  {
    return $this->date;
  }

  public function setDate($date)
  {
    $this->date = $date;
  }

  public function getAmount()
  {
    return $this->amount;
  }

  public function setAmount($amount)
  {
    $this->amount = $amount;
  }

  public function getReference()
  {
    return $this->reference;
  }

  public function setReference($reference)
  {
    $this->reference = $reference;
  }


}