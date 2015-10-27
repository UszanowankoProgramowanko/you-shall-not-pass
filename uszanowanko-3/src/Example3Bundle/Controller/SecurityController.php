<?php

namespace Example3Bundle\Controller;

use Example3Bundle\Form\Type\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $loginType = $this->createForm(new LoginType());

        return $this->render('app/example-3/login.html.twig', [
            'loginType' => $loginType->createView(),
            'error' => $error
        ]);
    }
}