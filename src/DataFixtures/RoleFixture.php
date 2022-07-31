<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RoleFixture extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $role = new Role();
        $role->setName('admin');
        $role->setDisplayName('Admin');
        $manager->persist($role);
        $manager->flush();
        $role->addPermission($this->getReference('ref_permission'));
        $this->addReference('role_admin', $role);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }
}
