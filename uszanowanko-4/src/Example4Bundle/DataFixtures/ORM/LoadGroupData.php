<?php

namespace Example4Bundle\DataFixtures\Orm;

use Example4Bundle\Entity\Group;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $groupsData = Yaml::parse(__DIR__.'/Fixtures/groups.yml');

        foreach($groupsData['groups'] as $key => $groupData) {
            $group = new Group();
            $group->setName($groupData['name']);
            $group->setRole($groupData['role']);

            $manager->persist($group);
            $manager->flush();

            $this->addReference($key, $group);
        }
    }

    public function getOrder()
    {
        return 2;
    }
}