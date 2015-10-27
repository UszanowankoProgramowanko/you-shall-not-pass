<?php

namespace Example2Bundle\Controller;

use Example2Bundle\Form\Type\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $loginType = $this->createForm(new LoginType());

        return $this->render('app/example-2/login.html.twig', [
            'loginType' => $loginType->createView(),
            'error' => $error
        ]);
    }
}