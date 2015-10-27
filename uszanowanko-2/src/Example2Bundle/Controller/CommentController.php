<?php

namespace Example2Bundle\Controller;

use Example2Bundle\Entity\Comment;
use Example2Bundle\Entity\User;
use Example2Bundle\Form\Type\AddCommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CommentController extends Controller
{
    public function addCommentAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

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

            return $this->redirectToRoute('example_2_main_page');
        }
    }

    public function removeCommentAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $comment = $this->getDoctrine()->getRepository('Example2Bundle:Comment')->findOneBy(['id' => $id]);

        if ($this->isGranted('ROLE_USER')) {
            if ($this->getUser()->getId() !== $comment->getUser()->getId()) {
                throw new AccessDeniedException();
            }
        }

        $comment->getUser()->removeComment($comment);

        $this->getDoctrine()->getManager()->remove($comment);
        $this->getDoctrine()->getManager()->persist($comment->getUser());
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('example_2_main_page');
    }
}
