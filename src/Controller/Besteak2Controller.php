<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Besteak2;
use App\Form\Besteak2Type;
use App\Repository\Besteak2Repository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Besteak2 controller.
 *
 * @Route("/{_locale}/besteak2")
 */
class Besteak2Controller extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, Besteak2Repository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Besteak2 entities.
     *
     * @Route("/", defaults={"page"=1}, name="besteak2_index", methods={"GET"})
     * @Route("/page{page}", name="besteak2_index_paginated", methods={"GET"})
     */
    public function index($page)
    {
        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $besteak2s = $this->repo->findBy( [], ['kodea'=>'ASC'] );

            $deleteForms = [];
            foreach ($besteak2s as $besteak2) {
                $deleteForms[$besteak2->getId()] = $this->createDeleteForm($besteak2)->createView();
            }

            return $this->render('besteak2/index.html.twig', ['besteak2s' => $besteak2s, 'deleteforms' => $deleteForms]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Besteak2 entity.
     *
     * @Route("/new", name="besteak2_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {
        if ($this->isGranted('ROLE_ADMIN'))
        {
            $besteak2 = new Besteak2();
            $form = $this->createForm(Besteak2Type::class, $besteak2);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($besteak2);
                $this->em->flush();

//                return $this->redirectToRoute('besteak2_show', array('id' => $besteak2->getId()));
                return $this->redirectToRoute('besteak2_index');                
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('besteak2/new.html.twig', ['besteak2' => $besteak2, 'form' => $form->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Besteak2 entity.
     *
     * @Route("/{id}", name="besteak2_show", methods={"GET"})
     */
    public function show(Besteak2 $besteak2): Response
    {
        $deleteForm = $this->createDeleteForm($besteak2);

        return $this->render('besteak2/show.html.twig', ['besteak2' => $besteak2, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Besteak2 entity.
     *
     * @Route("/{id}/edit", name="besteak2_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Besteak2 $besteak2)
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($besteak2->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($besteak2);
            $editForm = $this->createForm(Besteak2Type::class, $besteak2);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($besteak2);
                $this->em->flush();

                return $this->redirectToRoute('besteak2_edit', ['id' => $besteak2->getId()]);
            }

            return $this->render('besteak2/edit.html.twig', ['besteak2' => $besteak2, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Besteak2 entity.
     *
     * @Route("/{id}", name="besteak2_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Besteak2 $besteak2): RedirectResponse
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($besteak2->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($besteak2);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($besteak2);
                $this->em->flush();
            }
            return $this->redirectToRoute('besteak2_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Creates a form to delete a Besteak2 entity.
     *
     * @param Besteak2 $besteak2 The Besteak2 entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Besteak2 $besteak2)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('besteak2_delete', ['id' => $besteak2->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
