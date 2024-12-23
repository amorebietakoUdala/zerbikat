<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Azpisaila;
use App\Form\AzpisailaType;
use App\Repository\AzpisailaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Azpisaila controller.
 *
 * @Route("/{_locale}/azpisaila")
 */
class AzpisailaController extends AbstractController
{
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, AzpisailaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Azpisaila entities.
     *
     * @Route("/", defaults={"page"=1}, name="azpisaila_index", methods={"GET"})
     * @Route("/page{page}", name="azpisaila_index_paginated", methods={"GET"})
     */
    public function index($page)
    {
        if ($this->isGranted('ROLE_KUDEAKETA'))
        {
            $azpisailas = $this->repo->findBy( [], ['kodea'=>'ASC'] );

            $deleteForms = [];
            foreach ($azpisailas as $azpisaila) {
                $deleteForms[$azpisaila->getId()] = $this->createDeleteForm($azpisaila)->createView();
            }

            return $this->render('azpisaila/index.html.twig', ['azpisailas' => $azpisailas, 'deleteforms' => $deleteForms]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');            
        }
    }

    /**
     * Creates a new Azpisaila entity.
     *
     * @Route("/new", name="azpisaila_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {
        if ($this->isGranted('ROLE_ADMIN'))
        {
            $azpisaila = new Azpisaila();
            $form = $this->createForm(AzpisailaType::class, $azpisaila);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($azpisaila);
                $this->em->flush();

//                return $this->redirectToRoute('azpisaila_show', array('id' => $azpisaila->getId()));
                return $this->redirectToRoute('azpisaila_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('azpisaila/new.html.twig', ['azpisaila' => $azpisaila, 'form' => $form->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Azpisaila entity.
     *
     * @Route("/{id}", name="azpisaila_show", methods={"GET"})
     */
    public function show(Azpisaila $azpisaila): Response
    {
        $deleteForm = $this->createDeleteForm($azpisaila);

        return $this->render('azpisaila/show.html.twig', ['azpisaila' => $azpisaila, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Azpisaila entity.
     *
     * @Route("/{id}/edit", name="azpisaila_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Azpisaila $azpisaila)
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($azpisaila->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($azpisaila);
            $editForm = $this->createForm(AzpisailaType::class, $azpisaila);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($azpisaila);
                $this->em->flush();

                return $this->redirectToRoute('azpisaila_edit', ['id' => $azpisaila->getId()]);
            }

            return $this->render('azpisaila/edit.html.twig', ['azpisaila' => $azpisaila, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Azpisaila entity.
     *
     * @Route("/{id}", name="azpisaila_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Azpisaila $azpisaila): RedirectResponse
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($azpisaila->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($azpisaila);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($azpisaila);
                $this->em->flush();
            }
            return $this->redirectToRoute('azpisaila_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a form to delete a Azpisaila entity.
     *
     * @param Azpisaila $azpisaila The Azpisaila entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Azpisaila $azpisaila)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('azpisaila_delete', ['id' => $azpisaila->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
