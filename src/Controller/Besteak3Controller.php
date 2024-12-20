<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Besteak3;
use App\Form\Besteak3Type;
use App\Repository\Besteak3Repository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * Besteak3 controller.
 *
 * @Route("/{_locale}/besteak3")
 */
class Besteak3Controller extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, Besteak3Repository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Besteak3 entities.
     *
     * @Route("/", defaults={"page" = 1}, name="besteak3_index")
     * @Route("/page{page}", name="besteak3_index_paginated")
     * @Method("GET")
     */
    public function indexAction($page)
    {
        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $besteak3s = $this->repo->findBy( array(), array('kodea'=>'ASC') );

            $deleteForms = array();
            foreach ($besteak3s as $besteak3) {
                $deleteForms[$besteak3->getId()] = $this->createDeleteForm($besteak3)->createView();
            }

            return $this->render('besteak3/index.html.twig', array(
                'besteak3s' => $besteak3s,
                'deleteforms' => $deleteForms
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Besteak3 entity.
     *
     * @Route("/new", name="besteak3_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN'))
        {
            $besteak3 = new Besteak3();
            $form = $this->createForm(Besteak3Type::class, $besteak3);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($besteak3);
                $this->em->flush();

//                return $this->redirectToRoute('besteak3_show', array('id' => $besteak3->getId()));
                return $this->redirectToRoute('besteak3_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('besteak3/new.html.twig', array(
                'besteak3' => $besteak3,
                'form' => $form->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Besteak3 entity.
     *
     * @Route("/{id}", name="besteak3_show")
     * @Method("GET")
     */
    public function showAction(Besteak3 $besteak3)
    {
        $deleteForm = $this->createDeleteForm($besteak3);

        return $this->render('besteak3/show.html.twig', array(
            'besteak3' => $besteak3,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Besteak3 entity.
     *
     * @Route("/{id}/edit", name="besteak3_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Besteak3 $besteak3)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($besteak3->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($besteak3);
            $editForm = $this->createForm(Besteak3Type::class, $besteak3);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($besteak3);
                $this->em->flush();

                return $this->redirectToRoute('besteak3_edit', array('id' => $besteak3->getId()));
            }

            return $this->render('besteak3/edit.html.twig', array(
                'besteak3' => $besteak3,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Besteak3 entity.
     *
     * @Route("/{id}", name="besteak3_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Besteak3 $besteak3)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($besteak3->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($besteak3);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($besteak3);
                $this->em->flush();
            }
            return $this->redirectToRoute('besteak3_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a form to delete a Besteak3 entity.
     *
     * @param Besteak3 $besteak3 The Besteak3 entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Besteak3 $besteak3)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('besteak3_delete', array('id' => $besteak3->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
