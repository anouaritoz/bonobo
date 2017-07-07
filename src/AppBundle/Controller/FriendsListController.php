<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FriendsList;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Friendslist controller.
 *
 * @Route("friendslist")
 */
class FriendsListController extends Controller
{
    /**
     * Lists all friendsList entities.
     *
     * @Route("/", name="friendslist_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $friendsLists = $em->getRepository('AppBundle:FriendsList')->findAll();

        return $this->render('friendslist/index.html.twig', array(
            'friendsLists' => $friendsLists,
        ));
    }

    /**
     * Creates a new friendsList entity.
     *
     * @Route("/new", name="friendslist_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $friendsList = new Friendslist();
        $form = $this->createForm('AppBundle\Form\FriendsListType', $friendsList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($friendsList);
            $em->flush();

            return $this->redirectToRoute('friendslist_show', array('id' => $friendsList->getId()));
        }

        return $this->render('friendslist/new.html.twig', array(
            'friendsList' => $friendsList,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a friendsList entity.
     *
     * @Route("/{id}", name="friendslist_show")
     * @Method("GET")
     */
    public function showAction(FriendsList $friendsList)
    {
        $deleteForm = $this->createDeleteForm($friendsList);

        return $this->render('friendslist/show.html.twig', array(
            'friendsList' => $friendsList,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing friendsList entity.
     *
     * @Route("/{id}/edit", name="friendslist_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, FriendsList $friendsList)
    {
        $deleteForm = $this->createDeleteForm($friendsList);
        $editForm = $this->createForm('AppBundle\Form\FriendsListType', $friendsList);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('friendslist_edit', array('id' => $friendsList->getId()));
        }

        return $this->render('friendslist/edit.html.twig', array(
            'friendsList' => $friendsList,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a friendsList entity.
     *
     * @Route("/{id}", name="friendslist_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, FriendsList $friendsList)
    {
        $form = $this->createDeleteForm($friendsList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($friendsList);
            $em->flush();
        }

        return $this->redirectToRoute('friendslist_index');
    }

    /**
     * Creates a form to delete a friendsList entity.
     *
     * @param FriendsList $friendsList The friendsList entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(FriendsList $friendsList)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('friendslist_delete', array('id' => $friendsList->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
