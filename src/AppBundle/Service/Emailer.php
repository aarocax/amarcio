<?php
 
namespace AppBundle\Service;
 

class Emailer
{
    protected $mailer;
    protected $to;
    protected $asunto;
    protected $from;
    protected $conCopia;
    protected $attach;
 
    public function __construct($mailer, $to, $asunto, $from, $conCopia)
    {
        $this->mailer = $mailer;
        $this->to = $to;
        $this->asunto = $asunto;
        $this->from = $from;
        $this->conCopia = $conCopia;
    }
     
    public function send($texto, $email)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($this->asunto)
            ->setFrom($this->from)
            ->setTo($email)
            ->setContentType("text/html")
            ->setBody($texto);
        return $this->mailer->send($message);
    }

    public function attach($file)
    {
        $this->attach = \Swift_Attachment::fromPath(__DIR__.'/../../../web/sms-files/'.$file);
    }

    public function setTo($to)
    {
        $this->to = $to;
    }

    public function setSubject($subject)
    {
        $this->asunto = $subject;
    }
     
}