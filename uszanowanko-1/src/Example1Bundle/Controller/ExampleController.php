<?php

namespace Example1Bundle\Controller;

use Example1Bundle\Entity\Comment;
use Example1Bundle\Form\Type\AddCommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExampleController extends Controller
{
    public function indexAction()
    {
        $comment = new Comment();
        $addCommentType = $this->createForm(new AddCommentType(), $comment);

        $comments = $this->get('doctrine.orm.entity_manager')->getRepository('Example1Bundle:Comment')->findAll();

        return $this->render('app/example-1/index.html.twig', [
            'comments' => $comments,
            'addCommentType' => $addCommentType->createView()
        ]);
    }
}
