<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Besteak1;
use App\Form\Besteak1Type;
use App\Repository\Besteak1Repository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Besteak1 controller.
 *
 * @Route("/{_locale}/besteak1")
 */
class Besteak1Controller extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, Besteak1Repository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Besteak1 entities.
     *
     * @Route("/", defaults={"page" = 1}, name="besteak1_index")
     * @Route("/page{page}", name="besteak1_index_paginated") 
     * @Method("GET")
     */
    public function index($page)
    {
        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $besteak1s = $this->repo->findBy( [], ['kodea'=>'ASC'] );
            
            $deleteForms = [];
            foreach ($besteak1s as $besteak1) {
                $deleteForms[$besteak1->getId()] = $this->createDeleteForm($besteak1)->createView();
            }

            return $this->render('besteak1/index.html.twig', ['besteak1s' => $besteak1s, 'deleteforms' => $deleteForms]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Besteak1 entity.
     *
     * @Route("/new", name="besteak1_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        if ($this->isGranted('ROLE_ADMIN'))
        {
            $besteak1 = new Besteak1();
            $form = $this->createForm(Besteak1Type::class, $besteak1);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($besteak1);
                $this->em->flush();

//                return $this->redirectToRoute('besteak1_show', array('id' => $besteak1->getId()));
                return $this->redirectToRoute('besteak1_index');                
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('besteak1/new.html.twig', ['besteak1' => $besteak1, 'form' => $form->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Besteak1 entity.
     *
     * @Route("/{id}", name="besteak1_show")
     * @Method("GET")
     */
    public function show(Besteak1 $besteak1): Response
    {
        $deleteForm = $this->createDeleteForm($besteak1);

        return $this->render('besteak1/show.html.twig', ['besteak1' => $besteak1, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Besteak1 entity.
     *
     * @Route("/{id}/edit", name="besteak1_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Besteak1 $besteak1)
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($besteak1->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($besteak1);
            $editForm = $this->createForm(Besteak1Type::class, $besteak1);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($besteak1);
                $this->em->flush();
    
                return $this->redirectToRoute('besteak1_edit', ['id' => $besteak1->getId()]);
            }
    
            return $this->render('besteak1/edit.html.twig', ['besteak1' => $besteak1, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Deletes a Besteak1 entity.
     *
     * @Route("/{id}", name="besteak1_delete")
     * @Method("DELETE")
     */
    public function delete(Request $request, Besteak1 $besteak1): RedirectResponse
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($besteak1->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($besteak1);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($besteak1);
                $this->em->flush();
            }
            return $this->redirectToRoute('besteak1_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Creates a form to delete a Besteak1 entity.
     *
     * @param Besteak1 $besteak1 The Besteak1 entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Besteak1 $besteak1)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('besteak1_delete', ['id' => $besteak1->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
