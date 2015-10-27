<?php

namespace Example5Bundle\DataFixtures\Orm;

use Example5Bundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $containerInterface
     */
    public function setContainer(ContainerInterface $containerInterface = null)
    {
        $this->container = $containerInterface;
    }

    public function load(ObjectManager $manager)
    {
        $usersData = Yaml::parse(__DIR__.'/Fixtures/users.yml');

        foreach($usersData['users'] as $key => $userData) {
            $user = new User();

            $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);

            $user->setEmail($userData['email']);
            $user->setUsername($userData['username']);
            $user->setPassword($encoder->encodePassword($userData['password'],''));
            $user->setTwoFactor($userData['twoFactor']);

            $manager->persist($user);
            $manager->flush();

            $this->addReference($key, $user);
        }
    }

    public function getOrder()
    {
        return 1;
    }
}