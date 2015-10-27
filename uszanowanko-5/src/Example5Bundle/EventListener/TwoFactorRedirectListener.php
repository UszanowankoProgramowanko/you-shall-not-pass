<?php

namespace Example5Bundle\EventListener;

use Example5Bundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class TwoFactorRedirectListener
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param TokenStorage $tokenStorage
     * @param EngineInterface $templatingInterface
     * @param RouterInterface $router
     */
    public function __construct(TokenStorage $tokenStorage, EngineInterface $templatingInterface, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->templating = $templatingInterface;
        $this->router = $router;
    }

    public function onRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (!$this->tokenStorage->getToken()) {
            return;
        }

        if (!$this->tokenStorage->getToken()->getUser() instanceof User) {
            return;
        }

        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $session = $event->getRequest()->getSession();

        $key = sprintf('two_factor_auth_%s', $user->getUsername());
        if (!$session->has($key)) {
            return;
        }

        if ($session->get($key)) {
            return;
        }

        if ($request->getMethod() === 'POST') {
            if ($user->getTwoFactorCode() === $request->get('auth_code')) {
                $session->set($key, true);
                $redirect = new RedirectResponse($this->router->generate('example_5_main_page'));
                $event->setResponse($redirect);
                return;
            }
        }

        $response = $this->templating->renderResponse(':app/example-5:two_factor.html.twig');
        $event->setResponse($response);
    }
}