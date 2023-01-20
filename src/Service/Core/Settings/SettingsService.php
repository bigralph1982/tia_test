<?php

namespace App\Service\Core\Settings;

use App\Entity\Backend\Administrators;
use App\Entity\Core\Settings\Settings as Settings;
use Doctrine\ORM\EntityManagerInterface;

class SettingsService {

    protected $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function findSettings($locale = null) : ?Settings{


        $entity = $this->em->getRepository(Settings::class)->findAll();

        if ($entity) {
            return $entity[0];
        } else {
            return new Settings();
        }
    }

    public function findSenderName() {


        $settings = $this->findSettings();
        if ($settings) {
            return $settings->getSenderName();
        } else {
            return "Sync SARL";
        }
    }

    public function findRecordsPerPage() {


        $settings = $this->findSettings();
        if ($settings) {
            return $settings->getRecordsPerPage()? $settings->getRecordsPerPage() : 20;
        } else {
            return 20;
        }
    }

    public function findReplyToEmail() {


        $settings = $this->findSettings();
        if ($settings) {
            return $settings->getReplyToEmail();
        } else {
            return "info@sync.com.lb";
        }
    }

    public function findReceivedEmails() {
        $emails = [];
        $entities = $this->em->getRepository(Administrators::class)->findBy(["receiveEmails" => 1]);
        foreach ($entities as $entity) {
            $emails[] = $entity->getEmail();
        }
        if (count($emails)) {
            return $emails;
        }
        return ["info@sync.com.lb"];
    }

    public function findMailerSettings(){
        $settings = $this->findSettings();
        
        $data = [
            "host" => @$settings->getMailerHost(),
            "port" => @$settings->getMailerPort(),
            "encryption" => @$settings->getMailerEncryption(),
            "transport" => @$settings->getMailerTransport(),
            "username" => @$settings->getMailerUsername(),
            "email" => @$settings->getMailerEmail(),
            "password" => @$settings->getMailerPassword(),
        ];
        return $data;
    }

}
