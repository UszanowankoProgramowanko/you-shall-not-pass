<?php

namespace Example3Bundle\DataFixtures\Orm;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;
use Example3Bundle\Entity\Comment;
use Example3Bundle\Entity\User;

class LoadCommentData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $commentsDta = Yaml::parse(__DIR__.'/Fixtures/comments.yml');

        foreach ($commentsDta['comments'] as $commentData) {
            /** @var User $user */
            $user = $this->getReference($commentData['owner']);

            $comment = new Comment();
            $comment->setMessage($commentData['message']);
            $comment->setIsActive($commentData['isActive']);
            $comment->setUser($user);
            $user->addComment($comment);

            $manager->persist($comment);
            $manager->persist($user);
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 4;
    }
}