<?php

namespace Example2Bundle\Controller;

use Example2Bundle\Entity\Comment;
use Example2Bundle\Form\Type\AddCommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ExampleController extends Controller
{
    public function indexAction(Request $request)
    {
        $comment = new Comment();
        $addCommentType = $this->createForm(new AddCommentType(), $comment);

        $comments = $this->get('doctrine.orm.entity_manager')->getRepository('Example2Bundle:Comment')->findAll();

        return $this->render('app/example-2/index.html.twig', [
            'comments' => $comments,
            'addCommentType' => $addCommentType->createView()
        ]);
    }
}
