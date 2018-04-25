<?php
 
namespace AppBundle\Service;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;


class PersistManager
{
	private $manager;
	private $type;

	public function __construct($type=null)
	{
  	$this->type = $type;  
  }

  public function persist($data)
  {
  	switch ($this->type) {
  		case 'member':
  			$this->persistMember($data);
  			break;
  		case 'paypal-response':
        $this->persistPaypalResponse($data);
        break;
      case 'redsys-response':
        $this->persistRedsysResponse($data);
        break;
  		default:
  			# code...
  			break;
  	}
  	return true;
  }

  private function persistMember($data)
  {
  	$encoders = array(new CsvEncoder());
		$normalizers = array(new ObjectNormalizer());
		$serializer = new Serializer($normalizers, $encoders);
    $serializedData = $serializer->serialize($data, 'csv');
    $line = explode("\n", $serializedData);
  	$this->saveInfile($line[1], '/../../../var/data/member.csv');
  }

  private function persistPaypalResponse($data)
  {
    $encoders = array(new CsvEncoder());
    $normalizers = array(new ObjectNormalizer());
    $serializer = new Serializer($normalizers, $encoders);
    $serializedData = $serializer->serialize($data, 'csv');
    $line = explode("\n", $serializedData);
    $this->saveInfile($line[1], '/../../../var/data/donation.csv');
  }

  private function persistRedsysResponse($data)
  {
    $encoders = array(new CsvEncoder());
    $normalizers = array(new ObjectNormalizer());
    $serializer = new Serializer($normalizers, $encoders);
    $serializedData = $serializer->serialize($data, 'csv');
    $line = explode("\n", $serializedData);
    $this->saveInfile($line[1], '/../../../var/data/donation.csv');
  }

  private function saveInfile($string, $file)
  {
    try {
      $fp = fopen(__DIR__.$file, 'a+');
      fwrite($fp, $string."\n");
      fclose($fp);  
    } catch (Exception $e) {
      return false;
    }
    return true;
  }

  public function find($file, $search)
  {
    $f = fopen($file, "r");
    $result = false;
    while ($row = fgetcsv($f)) {
      if ($row[0] == $search) {
        $result = $row;
        break;
      }
    }
    fclose($f);
    return $result;
  }

  public function get($file)
  {
    $f = fopen($file, "r");
    $result = array();
    while ($row = fgetcsv($f)) {
      $result[] = $row;
    }
    fclose($f);
    return $result;
  }

  public function getMembers($file, $type)
  {
    $f = fopen($file, "r");
    $result = array();
    while ($row = fgetcsv($f)) {
      if ($row[2] == $type) {
        $result[] = $row;
      }
    }
    fclose($f);
    return $result;
  }




}