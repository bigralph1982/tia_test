<?php

namespace App\Traits\Core;


/**
 * Users  
 */
trait KeywordTrait
{

    /**
     * @var string
     *
     */
    protected $keyword;

    public function setKeyword(?string $keyword): self
    {
        $this->keyword = $keyword;

        return $this;
    }

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    
}
