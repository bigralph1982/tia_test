<?php

namespace App\Entity\Core\SEO;


use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use App\Repository\Core\SEO\SEOTagsRepository;
use App\Entity\Core\SEO\SEOTagsTranslations;
use App\Traits\Core\DatesTrait;
use App\Traits\Core\StatusTrait;

/**
 * SEOTags
 *
 * @ORM\Table(name="seo_tags")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=SEOTagsRepository::class)
 */
class SEOTags
{

    use DatesTrait;
    use StatusTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
    public $title;

    /**
     * @ORM\Column(name="code", type="string", length=255)
     */
    public $code;


    /**
     * @ORM\OneToMany(targetEntity=SEOTagsTranslations::class, mappedBy="translatable",  cascade={"persist","remove"})
     * @ORM\OrderBy({"id" = "desc"})
     *  
     */
    public $translations;
    protected $initrans;

    public function __construct()
    {

        $this->translations = new ArrayCollection();
        $this->initrans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTranslations()
    {
        if ($this->translations and $this->id) {

            foreach ($this->translations as $trans) {
                $this->initrans[$trans->getLocale()] = $trans;
            }

            $this->translations = $this->initrans;
        }

        return $this->translations;
    }

    public function addTranslation(SEOTagsTranslations $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations[] = $translation;
            $translation->setTranslatable($this);
        }

        return $this;
    }

    public function removeTranslation(SEOTagsTranslations $translation): self
    {
        if ($this->translations->removeElement($translation)) {
            // set the owning side to null (unless already changed)
            if ($translation->getTranslatable() === $this) {
                $translation->setTranslatable(null);
            }
        }

        return $this;
    }

}
