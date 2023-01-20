<?php

namespace App\Entity\Backend;

use App\Entity\Core\Users\Users;
use App\Repository\Backend\AdministratorsRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=AdministratorsRepository::class)
 * 
 * 
 */
class Administrators extends Users
{


    public $roles_array = [
        999 => 'ROLE_DEVELOPER',
        1 => 'ROLE_SUPER_ADMIN',
        2 => 'ROLE_ADMIN'
    ];

     /**
     * @var bool $receiveEmails
     *
     * @ORM\Column(name="receiveEmails", type="boolean", nullable=true)
     */
    private $receiveEmails;
    

    public function __construct()
    {
        parent::__construct();
    }

    public function getReceiveEmails(): ?bool
    {
        return $this->receiveEmails;
    }

    public function setReceiveEmails(?bool $receiveEmails): self
    {
        $this->receiveEmails = $receiveEmails;

        return $this;
    }  

   
}
