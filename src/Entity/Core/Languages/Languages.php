<?php

namespace App\Entity\Core\Languages;

use App\Traits\Core\DatesTrait;
use App\Traits\Core\StatusTrait;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\Core\Languages\LanguagesRepository;

/**
 * Languages
 *
 * @ORM\Table(name="languages")
 * @ORM\Entity(repositoryClass=LanguagesRepository::class)
 */
class Languages
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=3, unique=true)
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\Column(name="cid", type="smallint", unique=true)
     */
    private $cid;

    /**
     * @var int
     *
     * @ORM\Column(name="isMain", type="boolean", nullable=true)
     */
    private $isMain;

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

    public function getCid(): ?int
    {
        return $this->cid;
    }

    public function setCid(int $cid): self
    {
        $this->cid = $cid;

        return $this;
    }

    public function getIsMain(): ?bool
    {
        return $this->isMain;
    }

    public function setIsMain(?bool $isMain): self
    {
        $this->isMain = $isMain;

        return $this;
    }

}
