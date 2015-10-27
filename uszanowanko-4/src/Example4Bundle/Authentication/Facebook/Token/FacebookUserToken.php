<?php

namespace Example4Bundle\Authentication\Facebook\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class FacebookUserToken extends AbstractToken
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @param string $accessToken
     */
    public function __construct($accessToken)
    {
        parent::__construct();
        $this->accessToken = $accessToken;
    }

    public function getCredentials()
    {
        return $this->accessToken;
    }
}
