<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Service\PersistManager;

/**
 * @Route("/admin")
 */
class ReportController extends Controller
{
	/**
   * @Route("/donations", name="donations")
   */
  public function reportDonationsAction(Request $request)
  {
    $pm = new PersistManager();
    $responses = $pm->get(__DIR__.'/../../../var/data/donation.csv');
    $rows[] = "orderId, orderId, fecha, tipo, nombre, apellidos, dni, email, teléfono, cp, dirección, población, provincia, iban, medio, importe, frecuencia, acepta condiciones, como nos conoció, correspondencia, status, medio, fecha, importe, referencia\n";
    foreach ($responses as $key => $record) {
      $rows[] = implode(',', $record);
    }


    $content = implode("\n", $rows);

    $response = new Response($content);
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
    $response->headers->set('Content-Disposition', 'attachment; filename="donations.csv"');
  
    return $response;
  }

  /**
   * @Route("/members", name="members")
   */
  public function reportMembersAction(Request $request)
  {
    $pm = new PersistManager();
    $responses = $pm->get(__DIR__.'/../../../var/data/member.csv');
    $rows[] = "orderId, fecha, tipo, nombre, apellidos, dni, email, teléfono, cp, dirección, población, provincia, iban, medio, importe, frecuencia, acepta condiciones, como nos conoció, correspondencia\n";

    foreach ($responses as $key => $record) {
      $rows[] = implode(',', $record);
    }

    $content = implode("\n", $rows);

    $response = new Response($content);
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
    $response->headers->set('Content-Disposition', 'attachment; filename="members.csv"');
  
    return $response;
  }

  /**
   * @Route("/member-member", name="member-member")
   */
  public function reportMemberAction(Request $request)
  {
    $pm = new PersistManager();
    $responses = $pm->getMembers(__DIR__.'/../../../var/data/member.csv','socio');
    $rows[] = "orderId, fecha, tipo, nombre, apellidos, dni, email, teléfono, cp, dirección, población, provincia, iban, medio, importe, frecuencia, acepta condiciones, como nos conoció, correspondencia\n";
    foreach ($responses as $key => $record) {
      $rows[] = implode(',', $record);
    }

    $content = implode("\n", $rows);

    $response = new Response($content);
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
    $response->headers->set('Content-Disposition', 'attachment; filename="members-members.csv"');
  
    return $response;
  }

  /**
   * @Route("/member-donation", name="member-donation")
   */
  public function reportDonationAction(Request $request)
  {
    $pm = new PersistManager();
    $responses = $pm->getMembers(__DIR__.'/../../../var/data/member.csv','donante');
    $rows[] = "orderId, fecha, tipo, nombre, apellidos, dni, email, teléfono, cp, dirección, población, provincia, iban, medio, importe, frecuencia, acepta condiciones, como nos conoció, correspondencia\n";
    foreach ($responses as $key => $record) {
      $rows[] = implode(',', $record);
    }

    $content = implode("\n", $rows);

    $response = new Response($content);
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
    $response->headers->set('Content-Disposition', 'attachment; filename="member-donation.csv"');
  
    return $response;
  }
}