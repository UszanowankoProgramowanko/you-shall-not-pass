<?php

namespace Example3Bundle\Controller;

use Example3Bundle\Entity\Comment;
use Example3Bundle\Entity\User;
use Example3Bundle\Form\Type\AddCommentType;
use Example3Bundle\Voter\CommentVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CommentController extends Controller
{
    public function addCommentAction(Request $request)
    {
        $comment = new Comment();
        $this->denyAccessUnlessGranted(CommentVoter::ADD, $comment);

        $addCommentType = $this->createForm(new AddCommentType(), $comment);

        $addCommentType->handleRequest($request);
        if ($addCommentType->isValid()) {
            /** @var Comment $newComment */
            $newComment = $addCommentType->getData();

            /** @var User $user */
            $user = $this->getUser();
            $newComment->setUser($user);
            $user->addComment($newComment);

            $this->getDoctrine()->getManager()->persist($newComment);
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('example_3_main_page');
        }
    }

    public function removeCommentAction(Request $request, $id)
    {
        $comment = $this->getDoctrine()->getRepository('Example3Bundle:Comment')->findOneBy(['id' => $id]);

        $this->denyAccessUnlessGranted(CommentVoter::REMOVE, $comment);

        $comment->getUser()->removeComment($comment);

        $this->getDoctrine()->getManager()->remove($comment);
        $this->getDoctrine()->getManager()->persist($comment->getUser());
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('example_3_main_page');
    }
}
