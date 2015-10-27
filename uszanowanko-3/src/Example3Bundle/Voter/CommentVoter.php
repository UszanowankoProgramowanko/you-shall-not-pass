<?php

namespace Example3Bundle\Voter;

use Example3Bundle\Entity\Comment;
use Example3Bundle\Entity\User;
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
     * @return array
     */
    protected function getSupportedClasses()
    {
        return [Comment::class];
    }

    /**
     * @return array
     */
    protected function getSupportedAttributes()
    {
        return [self::ADD, self::REMOVE];
    }

    /**
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

        if (!$user instanceof UserInterface) {
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