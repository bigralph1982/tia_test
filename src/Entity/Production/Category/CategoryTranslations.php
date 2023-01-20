<?php
namespace App\Entity\Production\Category;


use App\Traits\Core\TextFieldsTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * SliderTranslations
 *
 * @ORM\Table(name="category_translations")
 * @ORM\Entity(repositoryClass=CategoryTranslationsRepository::class)
 */
class CategoryTranslations
{

    use TextFieldsTrait;

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
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="translations",  cascade={"persist"})
     * @ORM\JoinColumn(name="translatable_id", referencedColumnName="id",nullable=true)
     */
    protected $translatable;
 
  
  
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
     * Set locale
     *
     * @param string $locale
     *
     * @return CategoriesTranslations
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }
    /**
     * Get legend
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }



    public function setTranslatable($translatable)
    {
        $this->translatable = $translatable;
    }


    public function getTranslatable()
    {
        return $this->translatable;
    } 



}