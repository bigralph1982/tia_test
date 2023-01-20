<?php

namespace App\Traits\Core;

use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping as ORM;

/**
 * Users  
 * @MappedSuperClass
 * @ORM\HasLifecycleCallbacks() 
 */
trait StatusTrait
{

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint", nullable=true)
     */
    protected $status;
    protected $status_array = array('table.enabled' => 1, 'table.disabled' => 2);
    
    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusArray()
    {
        return $this->status_array;
    }
}
