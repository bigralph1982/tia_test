<?php

namespace App\Entity\Core\Settings;

use App\Traits\Core\UploadImagesTrait;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\Core\Settings\SettingsRepository;


/**
 * Settings
 *
 * @ORM\Table(name="settings")
 * @ORM\HasLifecycleCallbacks() 
 * @ORM\Entity(repositoryClass=SettingsRepository::class)
 */
class Settings
{

    use UploadImagesTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;



    /**
     * @var string
     *
     * @ORM\Column(name="senderName", type="string", nullable=true)
     */
    private $senderName;

    /**
     * @var string
     *
     * @ORM\Column(name="replyToEmail", type="string", nullable=true)
     */
    private $replyToEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="mailerHost", type="string", nullable=true)
     */
    private $mailerHost;

    /**
     * @var string
     *
     * @ORM\Column(name="mailerPort", type="string", nullable=true)
     */
    private $mailerPort;

    /**
     * @var string
     *
     * @ORM\Column(name="mailerEncryption", type="string", nullable=true)
     */
    private $mailerEncryption;

    /**
     * @var string
     *
     * @ORM\Column(name="mailerTransport", type="string", nullable=true)
     */
    private $mailerTransport;

    /**
     * @var string
     *
     * @ORM\Column(name="mailerUsername", type="string", nullable=true)
     */
    private $mailerUsername;

    /**
     * @var string
     *
     * @ORM\Column(name="mailerEmail", type="string", nullable=true)
     */
    private $mailerEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="mailerPassword", type="string", nullable=true)
     */
    private $mailerPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="recordsPerPage", type="string", nullable=true)
     */
    private $recordsPerPage;

    /**
     * @var string
     *
     * @ORM\Column(name="enableTwoStepAuthentication", type="boolean", nullable=true)
     */
    private $enableTwoStepAuthentication;

    

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set senderName
     *
     * @param string $senderName
     *
     * @return Settings
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;

        return $this;
    }

    /**
     * Get senderName
     *
     * @return string
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    public function getReplyToEmail(): ?string
    {
        return $this->replyToEmail;
    }

    public function setReplyToEmail(?string $replyToEmail): self
    {
        $this->replyToEmail = $replyToEmail;

        return $this;
    }

    public function getMailerHost(): ?string
    {
        return $this->mailerHost;
    }

    public function setMailerHost(?string $mailerHost): self
    {
        $this->mailerHost = $mailerHost;

        return $this;
    }

    public function getMailerPort(): ?string
    {
        return $this->mailerPort;
    }

    public function setMailerPort(?string $mailerPort): self
    {
        $this->mailerPort = $mailerPort;

        return $this;
    }

    public function getMailerEncryption(): ?string
    {
        return $this->mailerEncryption;
    }

    public function setMailerEncryption(?string $mailerEncryption): self
    {
        $this->mailerEncryption = $mailerEncryption;

        return $this;
    }

    public function getMailerTransport(): ?string
    {
        return $this->mailerTransport;
    }

    public function setMailerTransport(?string $mailerTransport): self
    {
        $this->mailerTransport = $mailerTransport;

        return $this;
    }

    public function getMailerUsername(): ?string
    {
        return $this->mailerUsername;
    }

    public function setMailerUsername(?string $mailerUsername): self
    {
        $this->mailerUsername = $mailerUsername;

        return $this;
    }

    public function getMailerEmail(): ?string
    {
        return $this->mailerEmail;
    }

    public function setMailerEmail(?string $mailerEmail): self
    {
        $this->mailerEmail = $mailerEmail;

        return $this;
    }

    public function getMailerPassword(): ?string
    {
        return $this->mailerPassword;
    }

    public function setMailerPassword(?string $mailerPassword): self
    {
        $this->mailerPassword = $mailerPassword;

        return $this;
    }

    public function getRecordsPerPage(): ?string
    {
        return $this->recordsPerPage;
    }

    public function setRecordsPerPage(?string $recordsPerPage): self
    {
        $this->recordsPerPage = $recordsPerPage;

        return $this;
    }

    public function getEnableTwoStepAuthentication(): ?bool
    {
        return $this->enableTwoStepAuthentication;
    }

    public function setEnableTwoStepAuthentication(?bool $enableTwoStepAuthentication): self
    {
        $this->enableTwoStepAuthentication = $enableTwoStepAuthentication;

        return $this;
    }

    
}
