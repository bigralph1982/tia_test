<?php

namespace App\Repository\Core\SEO;

use App\Entity\Core\SEO\SEOTags;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<SEOTags>
 * 
 * @method SEOTags|null find($id, $lockMode = null, $lockVersion = null)
 * @method SEOTags|null findOneBy(array $criteria, array $orderBy = null)
 * @method SEOTags[]    findAll()
 * @method SEOTags[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SEOTagsRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SEOTags::class);
    }

    public function findSEOTags($locale, $code)
    {

        $query = $this->getEntityManager()
            ->createQuery(
                "Select a,t FROM {$this->getEntityName()} a left join a.translatable t where t.code='{$code}' and a.locale='{$locale}'    "
            );
        try {

            return $query->getOneOrNullResult();
        } catch (Exception $e) {
        }
    }
}
