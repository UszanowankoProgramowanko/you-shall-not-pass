<?php

namespace Example4Bundle\Authentication\Facebook\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class FacebookFactory implements SecurityFactoryInterface
{

    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.facebook'.$id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('facebook.security.authentication.provider'));

        $listenerId = 'security.authentication.listener.facebook'.$id;

        $container->setDefinition(
            $listenerId,
            new DefinitionDecorator('facebook.security.authentication.listener')
        );

        return [$providerId, $listenerId, $defaultEntryPoint];
    }

    /**
     * Defines the position at which the provider is called.
     * Possible values: pre_auth, form, http, and remember_me.
     *
     * @return string
     */
    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'facebook';
    }

    public function addConfiguration(NodeDefinition $builder)
    {
    }
}