<?php

namespace Example4Bundle\Authentication\Facebook\Provider;

use Example4Bundle\Authentication\Facebook\Token\FacebookUserToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FacebookProvider implements AuthenticationProviderInterface
{
    /**
     * @var FacebookUserProvider
     */
    private $userProvider;

    /**
     * @param FacebookUserProvider $userProvider
     */
    public function __construct(FacebookUserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     *
     * @return bool true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof FacebookUserToken;
    }

    /**
     * Attempts to authenticate a TokenInterface object.
     *
     * @param TokenInterface $token The TokenInterface instance to authenticate
     *
     * @return TokenInterface An authenticated TokenInterface instance, never null
     *
     * @throws AuthenticationException if the authentication fails
     */
    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByToken($token->getCredentials());

        if ($user) {
            $authenticatedToken = new FacebookUserToken($token->getCredentials());
            $authenticatedToken->setUser($user);
            $authenticatedToken->setAuthenticated(true);

            return $authenticatedToken;
        }

        throw new AuthenticationException('Facebook authentication failed.');
    }
}