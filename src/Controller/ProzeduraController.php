<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Prozedura;
use App\Form\ProzeduraType;
use App\Repository\ProzeduraRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Prozedura controller.
 *
 * @Route("/{_locale}/prozedura")
 */
class ProzeduraController extends AbstractController
{
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, ProzeduraRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Prozedura entities.
     *
     * @Route("/", defaults={"page" = 1}, name="prozedura_index")
     * @Route("/page{page}", name="prozedura_index_paginated")
     * @Method("GET")
     */
    public function index($page)
    {

        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $prozeduras = $this->repo->findAll();

            $deleteForms = [];
            foreach ($prozeduras as $prozedura) {
                $deleteForms[$prozedura->getId()] = $this->createDeleteForm($prozedura)->createView();
            }

            return $this->render('prozedura/index.html.twig', ['prozeduras' => $prozeduras, 'deleteforms' => $deleteForms]);
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
    public function new(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN'))
        {
            $prozedura = new Prozedura();
            $form = $this->createForm(ProzeduraType::class, $prozedura);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($prozedura);
                $this->em->flush();

//                return $this->redirectToRoute('prozedura_show', array('id' => $prozedura->getId()));
                return $this->redirectToRoute('prozedura_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('prozedura/new.html.twig', ['prozedura' => $prozedura, 'form' => $form->createView()]);
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
    public function show(Prozedura $prozedura): Response
    {
        $deleteForm = $this->createDeleteForm($prozedura);

        return $this->render('prozedura/show.html.twig', ['prozedura' => $prozedura, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Prozedura entity.
     *
     * @Route("/{id}/edit", name="prozedura_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Prozedura $prozedura)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($prozedura->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($prozedura);
            $editForm = $this->createForm(ProzeduraType::class, $prozedura);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($prozedura);
                $this->em->flush();

                return $this->redirectToRoute('prozedura_edit', ['id' => $prozedura->getId()]);
            }

            return $this->render('prozedura/edit.html.twig', ['prozedura' => $prozedura, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
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
    public function delete(Request $request, Prozedura $prozedura): RedirectResponse
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($prozedura->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($prozedura);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($prozedura);
                $this->em->flush();
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
            ->setAction($this->generateUrl('prozedura_delete', ['id' => $prozedura->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
