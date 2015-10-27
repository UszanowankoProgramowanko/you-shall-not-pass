<?php

namespace Example4Bundle\Authentication\Facebook\Provider;

use Example4Bundle\Factory\UserFactory;
use Example4Bundle\Repository\UserRepository;
use Facebook\Facebook;

class FacebookUserProvider
{
    /**
     * @var Facebook
     */
    private $client;

    /**
     * @var UserRepository
     */
    private $usersRepository;

    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @param Facebook $client
     * @param UserRepository $usersRepository
     * @param UserFactory $userFactory
     */
    public function __construct(Facebook $client, UserRepository $usersRepository, UserFactory $userFactory)
    {
        $this->client = $client;
        $this->usersRepository = $usersRepository;
        $this->userFactory = $userFactory;
    }

    /**
     * @param $accessToken
     * @return \Example4Bundle\Entity\User|null|object
     */
    public function loadUserByToken($accessToken)
    {
        $response = $this->client->get('/me?fields=email', $accessToken);
        $facebookUser = $response->getGraphUser();

        $user = $this->usersRepository->findByEmail($facebookUser['email']);
        if (!$user) {
            $user = $this->userFactory->createUser($facebookUser['email']);
            $this->usersRepository->add($user);
        }

        return $user;
    }
}