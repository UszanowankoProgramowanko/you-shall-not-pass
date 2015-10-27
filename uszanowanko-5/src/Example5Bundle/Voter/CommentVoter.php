<?php

namespace Example5Bundle\Voter;

use Example5Bundle\Entity\Comment;
use Example5Bundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends AbstractVoter
{
    const ADD = 'ADD';
    const REMOVE = 'REMOVE';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $containerInterface
     */
    public function __construct(ContainerInterface $containerInterface)
    {
        $this->container = $containerInterface;
    }

    /**
     * Return an array of supported classes. This will be called by supportsClass.
     *
     * @return array an array of supported classes, i.e. array('Acme\DemoBundle\Model\Product')
     */
    protected function getSupportedClasses()
    {
        return [Comment::class];
    }

    /**
     * Return an array of supported attributes. This will be called by supportsAttribute.
     *
     * @return array an array of supported attributes, i.e. array('CREATE', 'READ')
     */
    protected function getSupportedAttributes()
    {
        return [self::ADD, self::REMOVE];
    }

    /**
     * Perform a single access check operation on a given attribute, object and (optionally) user
     * It is safe to assume that $attribute and $object's class pass supportsAttribute/supportsClass
     * $user can be one of the following:
     *   a UserInterface object (fully authenticated user)
     *   a string               (anonymously authenticated user).
     *
     * @param string $attribute
     * @param object $object
     * @param UserInterface|string $user
     *
     * @return bool
     */
    protected function isGranted($attribute, $object, $user = null)
    {
        if (!is_object($object)) {
            return false;
        }

        if (is_string($user)) {
            return false;
        }

        switch ($attribute)
        {
            case self::ADD:
                return true;
            case self::REMOVE:
                return $this->handleRemoveVoting($object, $user);
        }

        throw new \LogicException('How ?!');
    }

    /**
     * @param Comment $object
     * @param User $user
     * @return bool
     */
    private function handleRemoveVoting(Comment $object, User $user)
    {
        $authChecker = $this->container->get('security.authorization_checker');

        if ($authChecker->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if ($object->getUser()->getUsername() === $user->getUsername()) {
            return true;
        }

        return false;
    }
}