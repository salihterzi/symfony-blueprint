<?php

namespace App\DataFixtures;

use App\Entity\PermissionGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PermissionGroupFixture extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $permissionGroup = new PermissionGroup();
        $permissionGroup->setName('test_permission_group');
        $permissionGroup->setDisplayName('Test Permission Group');
        $manager->persist($permissionGroup);
        $this->addReference('ref_permission_group', $permissionGroup);

        $manager->flush();


    }

    /**
     * Get the order of this fixture
     *
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
