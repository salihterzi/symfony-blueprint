<?php

namespace App\DataFixtures;

use App\Entity\Permission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PermissionFixture extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $permission = new Permission();
        $permission->setName('test_permission');
        $permission->setDisplayName('Test Permission');
        $permission->setPermissionGroup($this->getReference('ref_permission_group'));
        $this->addReference('ref_permission', $permission);

        $manager->persist($permission);
        $manager->flush();
    }
    /**
     * Get the order of this fixture
     *
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
