<?php

namespace App\Traits\Core;

use App\Service\Backend\SlugsService;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping as ORM;

/**
 * Users  
 * @MappedSuperClass  
 */
trait SlugsTrait
{

    use SlugsService;

    /**
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    public $slug;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
