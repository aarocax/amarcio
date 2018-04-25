<?php 

namespace AppBundle\Service;

class Utils
{
  
	

  public static function formatAmount($amount)
  {
    $number = sprintf('%.2f', $amount);
    return str_replace(".", ",", $number)."€";
  }

    
}