<?php

namespace Example4Bundle\Controller;

use Example4Bundle\Entity\Comment;
use Example4Bundle\Form\Type\AddCommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ExampleController extends Controller
{
    public function indexAction(Request $request)
    {
        $comment = new Comment();
        $addCommentType = $this->createForm(new AddCommentType(), $comment);

        $comments = $this->get('doctrine.orm.entity_manager')->getRepository('Example4Bundle:Comment')->findAll();

        return $this->render('app/example-4/index.html.twig', [
            'comments' => $comments,
            'addCommentType' => $addCommentType->createView(),
            'facebookApiId' => $this->getParameter('facebookApiId')
        ]);
    }
}
