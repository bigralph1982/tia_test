<?php

namespace App\Repository\Backend;

use App\Entity\Backend\Administrators;
use App\Repository\Core\Users\UsersRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Administrators>
 * 
 * @method Administrators|null find($id, $lockMode = null, $lockVersion = null)
 * @method Administrators|null findOneBy(array $criteria, array $orderBy = null)
 * @method Administrators[]    findAll()
 * @method Administrators[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdministratorsRepository extends UsersRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Administrators::class);
    }

    
}
