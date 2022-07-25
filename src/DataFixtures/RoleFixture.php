<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $role = new Role();
        $role->setName('admin');
        $role->setDisplayName('Admin');
        $manager->persist($role);
        $manager->flush();

        $this->addReference('role_admin', $role);

        $manager->flush();
    }
}
