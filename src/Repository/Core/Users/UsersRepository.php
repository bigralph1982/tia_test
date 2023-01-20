<?php

namespace App\Repository\Core\Users;

use App\Entity\Core\Users\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Users>
 * 
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository  implements PasswordUpgraderInterface, UserLoaderInterface
{
    private $class;
    public function __construct(ManagerRegistry $registry, $class = Users::class)
    {
        $this->class = $class;
        parent::__construct($registry, $class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof Users) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }


    public function isUsernameAllowed(array $parameter)
    {

        $whereid = "";
        if (@$parameter['id']) {

            $whereid = " and u.id !={$parameter['id']} ";
        }

        $res = null;

        $username = strtolower($parameter['username']);

        $res = $this->createQueryBuilder('u')
            ->where("Lower(u.username) = :username {$whereid} and u.isActive in (1,2)")
            ->setParameter('username', strtolower($username))
            ->getQuery()
            ->getResult();

          
        return $res;
    }

    

    public function loadUserByUsername($username)
    {
        $res = $this->createQueryBuilder('u')
            ->where('Lower(u.username) = :username and u.isActive in (1,2) and u.status in (1,2) ')
            ->setParameter('username', strtolower($username))
            ->getQuery()
            ->getOneOrNullResult();

            return $res;
    }

    public function loadUserByIdentifier($username)
    {
        $res = $this->createQueryBuilder('u')
            ->where('Lower(u.username) = :username and u.isActive in (1,2) and u.status in (1,2) ')
            ->setParameter('username', strtolower($username))
            ->getQuery()
            ->getOneOrNullResult();

            return $res;
    }


    public function isEmailAllowed(array $parameter)
    {


        $whereid = "";
        if (@$parameter['id']) {

            $whereid = " and u.id !={$parameter['id']} ";
        }

        $res = null;

        $username = strtolower($parameter['email']);

        $res = $this->createQueryBuilder('u')
            ->where("Lower(u.email) = :email {$whereid} and u.isActive in (1,2)")
            ->setParameter('email', strtolower($username))
            ->getQuery()
            ->getResult();

          
        return $res;
    }

}
