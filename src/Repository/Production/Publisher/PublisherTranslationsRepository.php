<?php
namespace App\Repository\Production\Publisher;

use App\Entity\Production\Publisher\PublisherTranslations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class PublisherTranslationsRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublisherTranslations::class);
    }
}