<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Core\Languages\Languages;
use App\Entity\Backend\Administrators;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    protected $em;
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->em = $manager;
        
        $this->createDefaultLanguage();

        $this->createDefaultAdministrator();
        
        
    }

    public function createDefaultLanguage(){
        $languages = $this->em->getRepository(Languages::class)->findAll(); 
        if(count($languages)){
            return false;
        }
        $lang = new Languages();
        $lang->setCode("en");
        $lang->setTitle("English");
        $lang->setCid(1);
        $lang->setIsMain(1);
        $lang->setStatus(1);

        $this->em->persist($lang);
        $this->em->flush();
    }

    public function createDefaultAdministrator(){
        $admins = $this->em->getRepository(Administrators::class)->findAll(); 
        if(count($admins)){
            return false;
        }
        $admin = new Administrators();
        $admin->setUsername("syncadmin");
        $admin->setEmail("admin@sync.com.lb");
        $admin->setName("Sync Admin");
        $admin->setIsActive(1);
        $admin->setRoleId(999);
        $admin->setRoles([$admin->roles_array[$admin->getRoleId()]]);
        $admin->setStatus(1);
        $admin->setRandomSalt();

        $password = $this->encoder->encodePassword($admin, "Sync@101");
        $admin->setPassword($password);

        $this->em->persist($admin);
        $this->em->flush();
    }
}
