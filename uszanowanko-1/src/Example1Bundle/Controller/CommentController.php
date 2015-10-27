<?php

namespace Example1Bundle\Controller;

use Example1Bundle\Entity\Comment;
use Example1Bundle\Form\Type\AddCommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{
    public function addCommentAction(Request $request)
    {
        $comment = new Comment();
        $addCommentType = $this->createForm(new AddCommentType(), $comment);

        $addCommentType->handleRequest($request);
        if ($addCommentType->isValid()) {
            /** @var Comment $newComment */
            $newComment = $addCommentType->getData();

            $this->getDoctrine()->getManager()->persist($newComment);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('example_1_main_page');
        }

        return $this->redirectToRoute('example_1_main_page');
    }

    public function removeCommentAction(Request $request, $id)
    {
        $comment = $this->getDoctrine()->getRepository('Example1Bundle:Comment')->findOneBy(['id' => $id]);

        $this->getDoctrine()->getManager()->remove($comment);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('example_1_main_page');
    }
}
