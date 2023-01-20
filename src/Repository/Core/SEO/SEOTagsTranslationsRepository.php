<?php

namespace App\Repository\Core\SEO;

use App\Entity\Core\SEO\SEOTagsTranslations;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SEOTagsTranslations>
 * 
 * @method SEOTagsTranslations|null find($id, $lockMode = null, $lockVersion = null)
 * @method SEOTagsTranslations|null findOneBy(array $criteria, array $orderBy = null)
 * @method SEOTagsTranslations[]    findAll()
 * @method SEOTagsTranslations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SEOTagsTranslationsRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SEOTagsTranslations::class);
    }
}
