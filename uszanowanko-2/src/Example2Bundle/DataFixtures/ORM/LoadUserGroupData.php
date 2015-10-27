<?php

namespace Example2Bundle\DataFixtures\Orm;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Example2Bundle\Entity\User;
use Example2Bundle\Entity\Group;
use Symfony\Component\Yaml\Yaml;

class LoadUserGroupData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userGroups = Yaml::parse(__DIR__.'/Fixtures/user_group.yml');

        foreach($userGroups['user_groups'] as $key => $userGroupData) {
            /** @var User $user */
            $user = $this->getReference($key);
            foreach ($userGroupData['groups'] as $groupData) {
                /** @var Group $group */
                $group = $this->getReference($groupData);

                $user->addGroup($group);
            }

            $manager->persist($user);
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 3;
    }
}