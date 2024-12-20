<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Norkeskatu;
use App\Form\NorkeskatuType;
use App\Repository\NorkeskatuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Norkeskatu controller.
 *
 * @Route("/{_locale}/norkeskatu")
 */
class NorkeskatuController extends AbstractController
{
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, NorkeskatuRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Norkeskatu entities.
     *
     * @Route("/", defaults={"page" = 1}, name="norkeskatu_index")
     * @Route("/page{page}", name="norkeskatu_index_paginated")
     * @Method("GET")
     */
    public function index($page)
    {

        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $norkeskatus = $this->repo->findAll();

            $deleteForms = [];
            foreach ($norkeskatus as $norkeskatu) {
                $deleteForms[$norkeskatu->getId()] = $this->createDeleteForm($norkeskatu)->createView();
            }

            return $this->render('norkeskatu/index.html.twig', ['norkeskatus' => $norkeskatus, 'deleteforms' => $deleteForms]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Norkeskatu entity.
     *
     * @Route("/new", name="norkeskatu_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN'))
        {
            $norkeskatu = new Norkeskatu();
            $form = $this->createForm(NorkeskatuType::class, $norkeskatu);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($norkeskatu);
                $this->em->flush();

//                return $this->redirectToRoute('norkeskatu_show', array('id' => $norkeskatu->getId()));
                return $this->redirectToRoute('norkeskatu_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('norkeskatu/new.html.twig', ['norkeskatu' => $norkeskatu, 'form' => $form->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Norkeskatu entity.
     *
     * @Route("/{id}", name="norkeskatu_show")
     * @Method("GET")
     */
    public function show(Norkeskatu $norkeskatu): Response
    {
        $deleteForm = $this->createDeleteForm($norkeskatu);

        return $this->render('norkeskatu/show.html.twig', ['norkeskatu' => $norkeskatu, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Norkeskatu entity.
     *
     * @Route("/{id}/edit", name="norkeskatu_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Norkeskatu $norkeskatu)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($norkeskatu->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($norkeskatu);
            $editForm = $this->createForm(NorkeskatuType::class, $norkeskatu);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($norkeskatu);
                $this->em->flush();
    
                return $this->redirectToRoute('norkeskatu_edit', ['id' => $norkeskatu->getId()]);
            }
    
            return $this->render('norkeskatu/edit.html.twig', ['norkeskatu' => $norkeskatu, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Deletes a Norkeskatu entity.
     *
     * @Route("/{id}", name="norkeskatu_delete")
     * @Method("DELETE")
     */
    public function delete(Request $request, Norkeskatu $norkeskatu): RedirectResponse
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($norkeskatu->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($norkeskatu);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($norkeskatu);
                $this->em->flush();
            }
            return $this->redirectToRoute('norkeskatu_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Creates a form to delete a Norkeskatu entity.
     *
     * @param Norkeskatu $norkeskatu The Norkeskatu entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Norkeskatu $norkeskatu)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('norkeskatu_delete', ['id' => $norkeskatu->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
