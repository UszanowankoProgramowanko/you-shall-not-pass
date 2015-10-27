<?php

namespace Example4Bundle\Controller;

use Example4Bundle\Entity\Comment;
use Example4Bundle\Entity\User;
use Example4Bundle\Form\Type\AddCommentType;
use Example4Bundle\Voter\CommentVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CommentController extends Controller
{
    public function addCommentAction(Request $request)
    {
        if (!$this->isGranted(CommentVoter::ADD, new Comment())) {
            throw new AccessDeniedException();
        }

        $comment = new Comment();
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

            return $this->redirectToRoute('example_4_main_page');
        }
    }

    public function removeCommentAction(Request $request, $id)
    {
        $comment = $this->getDoctrine()->getRepository('Example4Bundle:Comment')->findOneBy(['id' => $id]);

        if (!$this->isGranted(CommentVoter::REMOVE, $comment)) {
            throw new AccessDeniedException();
        }

        $comment->getUser()->removeComment($comment);

        $this->getDoctrine()->getManager()->remove($comment);
        $this->getDoctrine()->getManager()->persist($comment->getUser());
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('example_4_main_page');
    }
}
