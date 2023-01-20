<?php

namespace App\Repository\Core\Languages;

use App\Entity\Core\Languages\Languages;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Languages>
 * 
 * @method Languages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Languages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Languages[]    findAll()
 * @method Languages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LanguagesRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Languages::class);
    }

    public function getNames()
    {
        $langs = $this->findBy(array('status' => 1));

        $array = [];
        foreach ($langs as $lang) {
            $array[$lang->getCode()] = $lang->getTitle();
        }


        return $array;
    }

    public function availableLanguages()
    {

        $langs = $this->findBy(array('status' => 1));
 
        $array = [];
        foreach ($langs as $lang) {
            $array[$lang->getTitle()] = $lang->getCode();
        }

        return $array;
    }

    function getGlobalLocale()
    {
        $langs = $this->findBy(array('status' => 1));

        $array = [];
        foreach ($langs as $i => $lang) {

            if ($lang->getIsMain() == 1) {
            }
            $array[$i] = $lang->getCode();
        }

        $array['main'] = $array[0];

        return $array;
    }

    public function removeMain()
    {
        $query = $this->getEntityManager()
            ->createQuery(
                "UPDATE {$this->getEntityName()} a set a.isMain=NULL"
            );

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function getMainLanguage()
    {
        $main = $this->findOneBy(array('isMain' => true, 'status' => 1));

        if (!$main) {

            $lang = $this->findAll();
            $main = $lang[0];
        }

        return $main->getCode();
    }
}
