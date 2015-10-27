<?php

namespace Example4Bundle\Controller;

use Example4Bundle\Form\Type\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $loginType = $this->createForm(new LoginType());

        return $this->render('app/example-4/login.html.twig', [
            'loginType' => $loginType->createView(),
            'error' => $error
        ]);
    }
}