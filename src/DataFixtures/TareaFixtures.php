<?php

namespace App\DataFixtures;

use App\Entity\Tarea;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TareaFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 20; $i++) {
            $tarea = new Tarea;
            $tarea->setDescripcion('Tarea del admin - '.$i);
            $tarea->setFinalizada(0);
            $tarea->setUsuario($this->getReference(UserFixtures::USUARIO_ADMIN_REFERENCIA));
            $manager->persist($tarea);
        }

        for ($i = 1; $i <= 20; $i++) {
            $tarea = new Tarea;
            $tarea->setDescripcion('Tarea del user - '.$i);
            $tarea->setFinalizada(0);
            $tarea->setUsuario($this->getReference(UserFixtures::USUARIO_USER_REFERENCIA));
            $manager->persist($tarea);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
