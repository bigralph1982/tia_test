<?php

namespace App\Service\Core\General;

use App\Service\Core\Settings\SettingsService;
use Doctrine\ORM\EntityManager;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailerService
{

    protected $mailer;
    protected $settings;
    protected $sender_email;
    protected $sender_name;
    protected $reply_to;
    protected $admin_emails;
    protected $mailer_settings;

    public function __construct(Swift_Mailer $mailer, SettingsService $settings, ContainerInterface $container)
    {
        //$this->mailer = $mailer;
        $this->settings = $settings;
        $this->mailer_settings = $this->settings->findMailerSettings();
        $this->sender_name = $this->settings->findSenderName();
        $this->reply_to = $this->settings->findReplyToEmail();
        //$this->sender_email = $container->getParameter("app.mailer_user");
        
        $this->admin_emails = $this->settings->findReceivedEmails();

        $this->initMailer();
    }

    private function initMailer(){

        //Create the Transport
        $transport = (new Swift_SmtpTransport($this->mailer_settings["host"], $this->mailer_settings["port"]))
        ->setUsername($this->mailer_settings["username"])
        ->setPassword($this->mailer_settings["password"])
        ;
        if($this->mailer_settings["encryption"]){
            $transport->setEncryption($this->mailer_settings["encryption"]);
        }
        $this->mailer = new Swift_Mailer($transport);
    }

    public function generateMailerMessage($array) {


        $reply_to = @$array['reply_to'];

        $to_email = @$array['to'] ? $array['to'] : $this->admin_emails;

        $subject = @$array['subject'] ? $array['subject'] : "";

        $content = @$array['content'] ? $array['content'] : "";

        $message = (new \Swift_Message(''))
                ->setSubject($subject)
                ->setFrom([$this->mailer_settings["email"] => $this->sender_name])
                ->setTo($to_email)
                ->setBody($content, "text/html")
        ;
        if($reply_to){
            $message->setReplyTo($reply_to);
        }

        if (@$array['attachment']) {
            $message->attach(\Swift_Attachment::fromPath($array['attachment']));
        }

        return $message;
    }

    public function sendMessage($row) {


        $message = $this->generateMailerMessage($row);

        try {
             $result = $this->mailer->send($message);
        } catch (\Swift_TransportException $e) {
            $result = array(
                false,
                'There was a problem sending email: ' . $e->getMessage()
            );
            
        }
        
        return $result;
     
    }

    
}
