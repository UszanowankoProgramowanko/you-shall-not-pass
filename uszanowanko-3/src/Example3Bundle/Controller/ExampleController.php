<?php

namespace Example3Bundle\Controller;

use Example3Bundle\Entity\Comment;
use Example3Bundle\Form\Type\AddCommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ExampleController extends Controller
{
    public function indexAction(Request $request)
    {
        $comment = new Comment();
        $addCommentType = $this->createForm(new AddCommentType(), $comment);

        $comments = $this->get('doctrine.orm.entity_manager')->getRepository('Example3Bundle:Comment')->findAll();

        return $this->render('app/example-3/index.html.twig', [
            'comments' => $comments,
            'addCommentType' => $addCommentType->createView()
        ]);
    }
}
