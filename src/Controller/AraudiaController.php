<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Araudia;
use App\Form\AraudiaType;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * Araudia controller.
 *
 * @Route("/{_locale}/araudia")
 */
class AraudiaController extends AbstractController
{
    /**
     * Lists all Araudia entities.
     *
     * @Route("/", defaults={"page" = 1}, name="araudia_index")
     * @Route("/page{page}", name="araudia_index_paginated")
     * @Method("GET")
     */
    public function indexAction($page)
    {
        if ($this->isGranted('ROLE_KUDEAKETA'))
        {
            $em = $this->getDoctrine()->getManager();
            $araudias = $em->getRepository(Araudia::class)->findBy( array(), array('kodea'=>'ASC') );

            $deleteForms = array();
            foreach ($araudias as $araudia) {
                $deleteForms[$araudia->getId()] = $this->createDeleteForm($araudia)->createView();
            }

            return $this->render('araudia/index.html.twig', array(
                'araudias' => $araudias,
                'deleteforms' => $deleteForms
            ));
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }


    /**
     * Creates a new Araudia entity.
     *
     * @Route("/new", name="araudia_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $araudium = new Araudia();
            $form = $this->createForm(AraudiaType::class, $araudium);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($araudium);
                $em->flush();

                return $this->redirectToRoute('araudia_index');
            }else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }
            

            return $this->render('araudia/new.html.twig', array(
                'araudium' => $araudium,
                'form' => $form->createView(),
            ));
        }else
        {
            //Baimenik ez
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Araudia entity.
     *
     * @Route("/{id}", name="araudia_show")
     * @Method("GET")
     */
    public function showAction(Araudia $araudium)
    {
        $deleteForm = $this->createDeleteForm($araudium);

        return $this->render('araudia/show.html.twig', array(
            'araudium' => $araudium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Araudia entity.
     *
     * @Route("/{id}/edit", name="araudia_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Araudia $araudium)
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($araudium->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($araudium);
            $editForm = $this->createForm(AraudiaType::class, $araudium);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($araudium);
                $em->flush();

                return $this->redirectToRoute('araudia_edit', array('id' => $araudium->getId()));
            }

            return $this->render('araudia/edit.html.twig', array(
                'araudium' => $araudium,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Araudia entity.
     *
     * @Route("/{id}", name="araudia_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Araudia $araudium)
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($araudium->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($araudium);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($araudium);
                $em->flush();
            }
            return $this->redirectToRoute('araudia_index');
        }else
        {
            //baimenik ez
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a form to delete a Araudia entity.
     *
     * @param Araudia $araudium The Araudia entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Araudia $araudium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('araudia_delete', array('id' => $araudium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
