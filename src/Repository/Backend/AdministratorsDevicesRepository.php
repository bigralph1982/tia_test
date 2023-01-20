<?php

namespace App\Repository\Backend;

use App\Entity\Backend\AdministratorsDevices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdministratorsDevices>
 * 
 * @method Administrators|null find($id, $lockMode = null, $lockVersion = null)
 * @method Administrators|null findOneBy(array $criteria, array $orderBy = null)
 * @method Administrators[]    findAll()
 * @method Administrators[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdministratorsDevicesRepository extends ServiceEntityRepository 
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdministratorsDevices::class);
    }
}
