<?php

namespace App\Traits\Core;

use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping as ORM;

/**
 * Users  
 * @MappedSuperClass 
 */
trait SEOFieldsTrait
{

    /**
     * @var string
     *
     * @ORM\Column(name="seo_description", type="text", nullable=true)
     */
    public $seo_description;

    /**
     * @var string
     *
     * @ORM\Column(name="seo_keywords", type="text", nullable=true)
     */
    public $seo_keywords;

    /**
     * @ORM\Column(name="seo_title", type="string", length=255, nullable=true)
     */
    public $seo_title;

    public function getSeoDescription(): ?string
    {
        return $this->seo_description;
    }

    public function setSeoDescription(?string $seo_description): self
    {
        $this->seo_description = $seo_description;

        return $this;
    }

    public function getSeoKeywords(): ?string
    {
        return $this->seo_keywords;
    }

    public function setSeoKeywords(?string $seo_keywords): self
    {
        $this->seo_keywords = $seo_keywords;

        return $this;
    }

    public function getSeoTitle(): ?string
    {
        return $this->seo_title;
    }

    public function setSeoTitle(?string $seo_title): self
    {
        $this->seo_title = $seo_title;

        return $this;
    }
}
