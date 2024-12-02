<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Prozedura;
use App\Form\ProzeduraType;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * Prozedura controller.
 *
 * @Route("/{_locale}/prozedura")
 */
class ProzeduraController extends AbstractController
{
    /**
     * Lists all Prozedura entities.
     *
     * @Route("/", defaults={"page" = 1}, name="prozedura_index")
     * @Route("/page{page}", name="prozedura_index_paginated")
     * @Method("GET")
     */
    public function indexAction($page)
    {

        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $em = $this->getDoctrine()->getManager();
            $prozeduras = $em->getRepository('App:Prozedura')->findAll();

            $deleteForms = array();
            foreach ($prozeduras as $prozedura) {
                $deleteForms[$prozedura->getId()] = $this->createDeleteForm($prozedura)->createView();
            }

            return $this->render('prozedura/index.html.twig', array(
                'prozeduras' => $prozeduras,
                'deleteforms' => $deleteForms
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Prozedura entity.
     *
     * @Route("/new", name="prozedura_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN'))
        {
            $prozedura = new Prozedura();
            $form = $this->createForm('App\Form\ProzeduraType', $prozedura);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($prozedura);
                $em->flush();

//                return $this->redirectToRoute('prozedura_show', array('id' => $prozedura->getId()));
                return $this->redirectToRoute('prozedura_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('prozedura/new.html.twig', array(
                'prozedura' => $prozedura,
                'form' => $form->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Prozedura entity.
     *
     * @Route("/{id}", name="prozedura_show")
     * @Method("GET")
     */
    public function showAction(Prozedura $prozedura)
    {
        $deleteForm = $this->createDeleteForm($prozedura);

        return $this->render('prozedura/show.html.twig', array(
            'prozedura' => $prozedura,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Prozedura entity.
     *
     * @Route("/{id}/edit", name="prozedura_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Prozedura $prozedura)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($prozedura->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($prozedura);
            $editForm = $this->createForm('App\Form\ProzeduraType', $prozedura);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($prozedura);
                $em->flush();

                return $this->redirectToRoute('prozedura_edit', array('id' => $prozedura->getId()));
            }

            return $this->render('prozedura/edit.html.twig', array(
                'prozedura' => $prozedura,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Prozedura entity.
     *
     * @Route("/{id}", name="prozedura_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Prozedura $prozedura)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($prozedura->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($prozedura);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($prozedura);
                $em->flush();
            }
            return $this->redirectToRoute('prozedura_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Creates a form to delete a Prozedura entity.
     *
     * @param Prozedura $prozedura The Prozedura entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Prozedura $prozedura)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('prozedura_delete', array('id' => $prozedura->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
