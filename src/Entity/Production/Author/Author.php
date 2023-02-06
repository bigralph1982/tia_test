<?php
namespace App\Entity\Production\Author;

use App\Entity\Production\Book\Book;
use App\Entity\Production\Publisher\Publisher;
use App\Repository\Production\Author\AuthorRepository;




use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;


use App\Traits\Core\DatesTrait;
use App\Traits\Core\StatusTrait;

/**
 *
 * @ORM\Table(name="author")
 * @ORM\HasLifecycleCallbacks() 
 * @ORM\Entity(repositoryClass=AuthorRepository::class)
 */
class Author
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

          /**
     * @ORM\ManyToMany(targetEntity=Book::class, inversedBy="parent")
     * @ORM\JoinTable(name="book_book")
     */
    private $book;

             /**
     * @ORM\ManyToMany(targetEntity=Publisher::class, inversedBy="parent")
     * @ORM\JoinTable(name="publisher_publisher")
     */
    private $publisher;

    /**
     * @ORM\OneToMany(targetEntity=AuthorTranslations::class, mappedBy="translatable",  cascade={"persist"})
     * @ORM\OrderBy({"locale" = "asc"})
     *  
     */
    public $translations;
    protected $initrans;



    public function __construct()
    {

        $this->translations = new ArrayCollection();
        $this->initrans = new ArrayCollection();
        $this->book = new ArrayCollection();
        $this->publisher = new ArrayCollection();
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

    /**
     * Get the value of book
     */ 
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Set the value of book
     *
     * @return  self
     */ 
    public function setBook($book)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get the value of publisher
     */ 
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * Set the value of publisher
     *
     * @return  self
     */ 
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }
}