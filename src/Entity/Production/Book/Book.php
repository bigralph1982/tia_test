<?php
namespace App\Entity\Production\Book;

use App\Entity\Production\Author\Author;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;


use App\Traits\Core\DatesTrait;
use App\Traits\Core\StatusTrait;

/**
 *
 * @ORM\Table(name="book")
 * @ORM\HasLifecycleCallbacks() 
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{

    use StatusTrait;
    use DatesTrait;
   

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
    public $productPlaceholder;

    //  /**
    //  * @ORM\ManyToMany(targetEntity=Author:class, inversedBy="author", cascade={"persist"})
    //  * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
    //  */
    // private $author_id;

    //  /**
    //  * @ORM\ManyToMany(targetEntity=Category:class, inversedBy="category", cascade={"persist"})
    //  * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
    //  */
    // private $category_id;

    /**
     * @ORM\OneToMany(targetEntity=BookTranslations::class, mappedBy="translatable",  cascade={"persist"})
     * @ORM\OrderBy({"locale" = "asc"})
     *  
     */
    public $translations;
    protected $initrans;



    public function __construct()
    {

        $this->translations = new ArrayCollection();
        $this->initrans = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

   

    public function addTranslations($translations)
    {
        $this->translations[] = $translations;
    }

    public function setTranslations($translations)
    {

        $this->translations = $translations;
        return $this;
    }

    /**
     *
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

    // /**
    //  * Get the value of author_id
    //  */ 
    // public function getAuthor_id()
    // {
    //     return $this->author_id;
    // }

    // /**
    //  * Set the value of author_id
    //  *
    //  * @return  self
    //  */ 
    // public function setAuthor_id($author_id)
    // {
    //     $this->author_id = $author_id;

    //     return $this;
    // }
}