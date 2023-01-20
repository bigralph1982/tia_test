<?php

namespace App\Service\Core\SEO;

use App\Entity\Core\SEO\SEOTags;
use Doctrine\ORM\EntityManager;

class SeoService
{

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function findTags($locale, $code)
    {

        return $this->em->getRepository(SEOTags::class)->findSEOTags($locale, $code);
    }
}
