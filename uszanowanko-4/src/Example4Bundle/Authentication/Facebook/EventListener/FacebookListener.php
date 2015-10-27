<?php

namespace Example4Bundle\Authentication\Facebook\EventListener;

use Example4Bundle\Authentication\Facebook\Token\FacebookUserToken;
use Facebook\Facebook;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class FacebookListener implements ListenerInterface
{
    private $tokenStorage;
    private $authenticationManager;
    private $client;
    private $session;

    public function __construct(
        TokenStorage $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        Facebook $client,
        SessionInterface $session
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->client = $client;
        $this->session = $session;
    }

    public function handle(GetResponseEvent $event)
    {
        if (!$this->session->has('fb_access_token')) {
            $helper = $this->client->getJavaScriptHelper();
            $accessToken = $helper->getAccessToken();

            if (!$accessToken) {
                return;
            }

            $oAuth2Client = $this->client->getOAuth2Client();
            $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken((string) $accessToken);

            $this->session->set('fb_access_token', (string) $longLivedAccessToken);
        }

        $token = new FacebookUserToken($this->session->get('fb_access_token'));

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($authToken);
            return;
        } catch(AuthenticationException $exception) {
            //log exceptions here
        }

        $response = new Response();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
    }
}