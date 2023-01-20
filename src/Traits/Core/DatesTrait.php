<?php

namespace App\Traits\Core;

use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping as ORM;

/**
 * Users  
 * @MappedSuperClass
 * @ORM\HasLifecycleCallbacks() 
 */
trait DatesTrait
{

    /**
     * @var \DateTimeInterface $createDate
     *
     * @ORM\Column(name="createDate", type="datetime", nullable=true)
     */
    protected $createDate;

    /**
     * @var \DateTimeInterface $updateDate
     *
     * @ORM\Column(name="updateDate", type="datetime", nullable=true)
     */
    protected $updateDate;



    /**
     * Set createDate
     * @ORM\PrePersist
     * @param datetime $createDate
     */
    public function setCreateDate()
    {
        if (!$this->getCreateDate()) {
            $this->createDate = new \Datetime();
        }
        return $this;
    }

    /**
     * Get createDate
     *
     * @return datetime 
     * 
     */
    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    /**
     * Set updateDate
     * @ORM\PreUpdate
     * @param datetime $updateDate
     */
    public function setUpdateDate()
    {
        $this->updateDate = new \Datetime();
        return $this;
    }

    /**
     * Get updateDate
     *
     * @return datetime 
     * 
     */
    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }
}
