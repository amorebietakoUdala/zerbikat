<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Kanala;
use App\Form\KanalaType;
use App\Repository\KanalaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Kanala controller.
 *
 * @Route("/{_locale}/kanala")
 */
class KanalaController extends AbstractController
{
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, KanalaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Kanala entities.
     *
     * @Route("/", defaults={"page"=1}, name="kanala_index", methods={"GET"})
     * @Route("/page{page}", name="kanala_index_paginated", methods={"GET"})
     */
    public function index($page)
    {

        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $kanalas = $this->repo->findBy( [], ['kanalmota'=>'ASC'] );

            $deleteForms = [];
            foreach ($kanalas as $kanala) {
                $deleteForms[$kanala->getId()] = $this->createDeleteForm($kanala)->createView();
            }

            return $this->render('kanala/index.html.twig', ['kanalas' => $kanalas, 'deleteforms' => $deleteForms]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Kanala entity.
     *
     * @Route("/new", name="kanala_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN'))
        {
            $kanala = new Kanala();
            $form = $this->createForm(KanalaType::class, $kanala);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($kanala);
                $this->em->flush();

//                return $this->redirectToRoute('kanala_show', array('id' => $kanala->getId()));
                return $this->redirectToRoute('kanala_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('kanala/new.html.twig', ['kanala' => $kanala, 'form' => $form->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Kanala entity.
     *
     * @Route("/{id}", name="kanala_show", methods={"GET"})
     */
    public function show(Kanala $kanala): Response
    {
        $deleteForm = $this->createDeleteForm($kanala);

        return $this->render('kanala/show.html.twig', ['kanala' => $kanala, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Kanala entity.
     *
     * @Route("/{id}/edit", name="kanala_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Kanala $kanala)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($kanala->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($kanala);
            $editForm = $this->createForm(KanalaType::class, $kanala);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($kanala);
                $this->em->flush();

                return $this->redirectToRoute('kanala_edit', ['id' => $kanala->getId()]);
            }

            return $this->render('kanala/edit.html.twig', ['kanala' => $kanala, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Kanala entity.
     *
     * @Route("/{id}", name="kanala_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Kanala $kanala): RedirectResponse
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($kanala->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {        
            $form = $this->createDeleteForm($kanala);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($kanala);
                $this->em->flush();
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
     * @return Form The form
     */
    private function createDeleteForm(Kanala $kanala)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('kanala_delete', ['id' => $kanala->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
