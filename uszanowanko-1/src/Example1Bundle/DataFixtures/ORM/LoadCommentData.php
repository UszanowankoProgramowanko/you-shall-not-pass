<?php

namespace Example1Bundle\DataFixtures\Orm;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;
use Example1Bundle\Entity\Comment;

class LoadCommentData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $commentsDta = Yaml::parse(__DIR__.'/Fixtures/comments.yml');

        foreach ($commentsDta['comments'] as $commentData) {
            $comment = new Comment();
            $comment->setMessage($commentData['message']);

            $manager->persist($comment);
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 4;
    }
}