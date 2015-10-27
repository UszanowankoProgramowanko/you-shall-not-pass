<?php

namespace Example4Bundle\Factory;

use Doctrine\ORM\EntityRepository;
use Example4Bundle\Entity\User;
use Example4Bundle\Entity\Group;

class UserFactory
{
    /**
     * @var EntityRepository
     */
    private $userRepository;

    /**
     * @var EntityRepository
     */
    private $groupRepository;

    /**
     * @param EntityRepository $userRepository
     * @param EntityRepository $groupRepository
     */
    public function __construct(EntityRepository $userRepository, EntityRepository $groupRepository)
    {
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
    }

    /**
     * @param string $email
     * @return User
     */
    public function createUser($email)
    {
        /** @var Group $group */
        $group = $this->groupRepository->findOneBy(['name' => 'User']);

        $user = new User();
        $user->addGroup($group);
        $user->setEmail($email);
        $user->setUsername($this->createUserName());

        return $user;
    }

    /**
     * @param int $length
     * @return string
     */
    private function createUserName($length = 10)
    {
        $validCharacters = 'abcdefghijklmnoprstuwyz';
        $username = '';

        for ($i=0; $i<$length; $i++) {
            $username .= $validCharacters[rand(0, strlen($validCharacters)-1)];
        }

        $user = $this->userRepository->findOneBy(['username' => $username]);
        if ($user) {
            return $this->createUserName();
        }

        return $username;
    }
}