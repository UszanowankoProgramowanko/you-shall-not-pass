<?php

namespace Example5Bundle\EventListener;

use Example5Bundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class TwoFactorAuthenticationListener implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @param RouterInterface $routerInterface
     */
    public function __construct(RouterInterface $routerInterface, EntityManager $manager, \Swift_Mailer $mailer)
    {
        $this->router = $routerInterface;
        $this->manager = $manager;
        $this->mailer = $mailer;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $user = $token->getUser();
        if ($user instanceof User) {
            if ($user->isTwoFactor()) {
                $twoFactorCode = $this->generateCode(8);

                $user->setTwoFactorCode($twoFactorCode);
                $this->manager->persist($user);
                $this->manager->flush();

                $message = \Swift_Message::newInstance()
                    ->setSubject("Two factor code")
                    ->setTo($user->getEmail())
                    ->setFrom('uszanowanko@test.pl')
                    ->setBody($twoFactorCode);

                $this->mailer->send($message);

                $request->getSession()->set(sprintf('two_factor_auth_%s', $user->getUsername()), null);

                return new RedirectResponse($this->router->generate('example_5_main_page'));
            }
        }

        return new RedirectResponse($this->router->generate('example_5_main_page'));
    }

    private function generateCode($length)
    {
        $number = '0123456789';
        $code = '';

        for ($i=0; $i<$length; $i++) {
            $code .= $number[rand(0, strlen($number)-1)];
        }

        return $code;
    }
}