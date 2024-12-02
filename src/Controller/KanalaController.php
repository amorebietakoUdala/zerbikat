<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Kanala;
use App\Form\KanalaType;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * Kanala controller.
 *
 * @Route("/{_locale}/kanala")
 */
class KanalaController extends AbstractController
{
    /**
     * Lists all Kanala entities.
     *
     * @Route("/", defaults={"page" = 1}, name="kanala_index")
     * @Route("/page{page}", name="kanala_index_paginated") 
     * @Method("GET")
     */
    public function indexAction($page)
    {

        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $em = $this->getDoctrine()->getManager();
            $kanalas = $em->getRepository('App:Kanala')
                ->findBy( array(), array('kanalmota'=>'ASC') );

            $deleteForms = array();
            foreach ($kanalas as $kanala) {
                $deleteForms[$kanala->getId()] = $this->createDeleteForm($kanala)->createView();
            }

            return $this->render('kanala/index.html.twig', array(
                'kanalas' => $kanalas,
                'deleteforms' => $deleteForms
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Kanala entity.
     *
     * @Route("/new", name="kanala_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN'))
        {
            $kanala = new Kanala();
            $form = $this->createForm('App\Form\KanalaType', $kanala);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($kanala);
                $em->flush();

//                return $this->redirectToRoute('kanala_show', array('id' => $kanala->getId()));
                return $this->redirectToRoute('kanala_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('kanala/new.html.twig', array(
                'kanala' => $kanala,
                'form' => $form->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Kanala entity.
     *
     * @Route("/{id}", name="kanala_show")
     * @Method("GET")
     */
    public function showAction(Kanala $kanala)
    {
        $deleteForm = $this->createDeleteForm($kanala);

        return $this->render('kanala/show.html.twig', array(
            'kanala' => $kanala,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Kanala entity.
     *
     * @Route("/{id}/edit", name="kanala_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Kanala $kanala)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($kanala->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($kanala);
            $editForm = $this->createForm('App\Form\KanalaType', $kanala);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($kanala);
                $em->flush();

                return $this->redirectToRoute('kanala_edit', array('id' => $kanala->getId()));
            }

            return $this->render('kanala/edit.html.twig', array(
                'kanala' => $kanala,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Kanala entity.
     *
     * @Route("/{id}", name="kanala_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Kanala $kanala)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($kanala->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {        
            $form = $this->createDeleteForm($kanala);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($kanala);
                $em->flush();
            }
            return $this->redirectToRoute('kanala_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Creates a form to delete a Kanala entity.
     *
     * @param Kanala $kanala The Kanala entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Kanala $kanala)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('kanala_delete', array('id' => $kanala->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
