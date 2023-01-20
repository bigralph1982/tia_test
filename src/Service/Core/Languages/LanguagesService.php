<?php

namespace App\Service\Core\Languages;

use App\Entity\Core\Languages\Languages;
use App\Repository\Core\Languages\LanguagesRepository;
use Doctrine\ORM\EntityManagerInterface;

class LanguagesService
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LanguagesRepository
     */
    protected $languagesRepository;

    public function __construct(EntityManagerInterface $em, LanguagesRepository $languagesRepository)
    {
        $this->em = $em;
        $this->languagesRepository = $languagesRepository;
    }

    public function removeMain()
    {

        return $this->languagesRepository->removeMain();
    }

    public function getLanguagesNames()
    {

        return $this->languagesRepository->getNames();
    }

    public function getLanguages()
    {

        return $this->languagesRepository->availableLanguages();
    }

    public function getMain()
    {
        return $this->languagesRepository->getMainLanguage();
    }

    public function getWebLocale()
    {
        return $this->languagesRepository->getGlobalLocale();
    }

    public function getLanguageEntity($code)
    {
        return $this->em->getRepository(Languages::class)->findOneBy(array('code' => $code));
    }
}
