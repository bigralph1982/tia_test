<?php
namespace App\Entity\Production\Publisher;


use Doctrine\ORM\Mapping as ORM;

use App\Traits\Core\SEOFieldsTrait;
use App\Entity\Production\Publisher\Publisher;
use App\Repository\Production\Publisher\PublisherTranslationsRepository;



/**
 * SEOTagsTranslations
 *
 * @ORM\Table(name="publishertranslations")
 * @ORM\HasLifecycleCallbacks() 
 * @ORM\Entity(repositoryClass=PublisherTranslationsRepository::class)
 */
class PublisherTranslations
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
     * @ORM\Column(name="date", type="string", length=255)
     */
    public $publisherdate;


    /**
     * @ORM\Column(name="locale", type="string", length=5, nullable=true)
     */
    public $locale;

    /**
     * @ORM\ManyToOne(targetEntity=Publisher::class, inversedBy="translations",  cascade={"persist" })
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

    public function getTranslatable(): ?Publisher
    {
        return $this->translatable;
    }

    public function setTranslatable(?Publisher $translatable): self
    {
        $this->translatable = $translatable;

        return $this;
    }



    /**
     * Get the value of publisherdate
     */ 
    public function getPublisherdate()
    {
        return $this->publisherdate;
    }

    /**
     * Set the value of publisherdate
     *
     * @return  self
     */ 
    public function setPublisherdate($publisherdate)
    {
        $this->publisherdate = $publisherdate;

        return $this;
    }
}
