<?php
namespace App\Repository\Production\Author;

use App\Entity\Production\Author\AuthorTranslations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class AuthorTranslationsRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthorTranslations::class);
    }
}