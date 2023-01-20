<?php

namespace App\Entity\Core\SEO;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Core\SEO\SEOTags;
use App\Repository\Core\SEO\SEOTagsTranslationsRepository;
use App\Traits\Core\SEOFieldsTrait;


/**
 * SEOTagsTranslations
 *
 * @ORM\Table(name="seo_tags_translations")
 * @ORM\HasLifecycleCallbacks() 
 * @ORM\Entity(repositoryClass=SEOTagsTranslationsRepository::class)
 */
class SEOTagsTranslations
{

    use SEOFieldsTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(name="locale", type="string", length=5, nullable=true)
     */
    public $locale;

    /**
     * @ORM\ManyToOne(targetEntity=SEOTags::class, inversedBy="translations",  cascade={"persist" })
     * @ORM\JoinColumn(name="translatable_id", referencedColumnName="id",nullable=true)
     */
    protected $translatable;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getTranslatable(): ?SEOTags
    {
        return $this->translatable;
    }

    public function setTranslatable(?SEOTags $translatable): self
    {
        $this->translatable = $translatable;

        return $this;
    }


}
