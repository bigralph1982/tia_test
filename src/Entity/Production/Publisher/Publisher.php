<?php
namespace App\Entity\Production\Publisher;


use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use App\Traits\Core\DatesTrait;
use App\Traits\Core\StatusTrait;
use App\Entity\Production\Publisher\PublisherTranslations;
use App\Repository\Production\Publisher\PublisherRepository;


/**

 *
 * @ORM\Table(name="publisher")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=PublisherRepository::class)
 */
class Publisher
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
     * @var int
     *
     * @ORM\Column(name="ordering", type="integer", nullable=true)
     */
    private $ordering;

    /**
     * @ORM\OneToMany(targetEntity=PublisherTranslations::class, mappedBy="translatable",  cascade={"persist","remove"})
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

    public function addTranslation(PublisherTranslations $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations[] = $translation;
            $translation->setTranslatable($this);
        }

        return $this;
    }

    public function removeTranslation(PublisherTranslations $translation): self
    {
        if ($this->translations->removeElement($translation)) {
            // set the owning side to null (unless already changed)
            if ($translation->getTranslatable() === $this) {
                $translation->setTranslatable(null);
            }
        }

        return $this;
    }


    /**
     * Get the value of ordering
     *
     * @return  int
     */ 
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set the value of ordering
     *
     * @param  int  $ordering
     *
     * @return  self
     */ 
    public function setOrdering(int $ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }
}
