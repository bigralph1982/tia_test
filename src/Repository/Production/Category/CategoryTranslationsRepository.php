<?php
namespace App\Repository\Production\Category;

use App\Entity\Production\Category\CategoryTranslations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class CategoryTranslationsRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryTranslations::class);
    }
}