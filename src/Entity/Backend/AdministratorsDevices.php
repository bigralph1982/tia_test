<?php

namespace App\Entity\Backend;

use App\Traits\Core\DatesTrait;
use App\Traits\Core\StatusTrait;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\Backend\AdministratorsDevicesRepository;

/**
 * @ORM\HasLifecycleCallbacks() 
 * 
 * @ORM\Entity(repositoryClass=AdministratorsDevicesRepository::class)
 * 
 */
class AdministratorsDevices implements UserInterface
{

    use StatusTrait;
    use DatesTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @var datetime $lastLogin
     *
     * @ORM\Column(name="lastLogin", type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @ORM\ManyToOne(targetEntity=Administrators::class,  cascade={"persist" })
     * @ORM\JoinColumn(name="administrator_id", referencedColumnName="id",nullable=true)
     */
    protected $administrator;


   

    /**
     * @var string
     *
     * @ORM\Column(name="deviceUUID", type="text", nullable=true)
     */
    private $deviceUUID;

    /**
     * @var string
     *
     * @ORM\Column(name="deviceModel", type="text", nullable=true)
     */
    private $deviceModel;

    /**
     * @var string
     *
     * @ORM\Column(name="apiKey", type="string", length=255, nullable=true)
     */
    private $apiKey;


    public function getDeviceUUID(): ?string
    {
        return $this->deviceUUID;
    }

    public function setDeviceUUID(?string $deviceUUID): self
    {
        $this->deviceUUID = $deviceUUID;

        return $this;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

   

    public function setApiKey(?string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Set lastLogin
     * @ORM\PreUpdate
     * @ORM\PrePersist
     * @param datetime $updateDate
     */
    public function setLastLogin() {
        $this->lastLogin = new \Datetime();
        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return datetime 
     */
    public function getLastLogin() {
        return $this->lastLogin;
    }

    public function getUserIdentifier(){
        return $this->getApiKey();
    }

    public function getRoles(){
        return $this->getAdministrator()->getRoles();
    }

    public function getPassword(){
        return $this->getAdministrator()->getPassword();
    }

    public function eraseCredentials()
    {
        return $this;
    }

    public function getSalt()
    {
        return $this->getAdministrator()->getSalt();
    }

    public function getUsername()
    {   
        return $this->getAdministrator()->getUsername();
    }


    /**
     * Get the value of administrator
     */ 
    public function getAdministrator()
    {
        return $this->administrator;
    }

    /**
     * Set the value of administrator
     *
     * @return  self
     */ 
    public function setAdministrator($administrator)
    {
        $this->administrator = $administrator;

        return $this;
    }

    public function getDeviceModel(): ?string
    {
        return $this->deviceModel;
    }

    public function setDeviceModel(?string $deviceModel): self
    {
        $this->deviceModel = $deviceModel;

        return $this;
    }
}
