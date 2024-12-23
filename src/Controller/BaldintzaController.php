<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Baldintza;
use App\Form\BaldintzaType;
use App\Repository\BaldintzaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Baldintza controller.
 *
 * @Route("/{_locale}/baldintza")
 */
class BaldintzaController extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, BaldintzaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Baldintza entities.
     *
     * @Route("/", defaults={"page"=1}, name="baldintza_index", methods={"GET"})
     * @Route("/page{page}", name="baldintza_index_paginated", methods={"GET"})
     */
    public function index($page)
    {
        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $baldintzas = $this->repo->findAll();

            $deleteForms = [];
            foreach ($baldintzas as $baldintza) {
                $deleteForms[$baldintza->getId()] = $this->createDeleteForm($baldintza)->createView();
            }

            return $this->render('baldintza/index.html.twig', ['baldintzas' => $baldintzas, 'deleteforms' => $deleteForms]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Baldintza entity.
     *
     * @Route("/new", name="baldintza_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {
        if ($this->isGranted('ROLE_ADMIN'))
        {
            $baldintza = new Baldintza();
            $form = $this->createForm(BaldintzaType::class, $baldintza);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($baldintza);
                $this->em->flush();

//                return $this->redirectToRoute('baldintza_show', array('id' => $baldintza->getId()));
                return $this->redirectToRoute('baldintza_index');

            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('baldintza/new.html.twig', ['baldintza' => $baldintza, 'form' => $form->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Baldintza entity.
     *
     * @Route("/{id}", name="baldintza_show", methods={"GET"})
     */
    public function show(Baldintza $baldintza): Response
    {
        $deleteForm = $this->createDeleteForm($baldintza);

        return $this->render('baldintza/show.html.twig', ['baldintza' => $baldintza, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Baldintza entity.
     *
     * @Route("/{id}/edit", name="baldintza_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Baldintza $baldintza)
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($baldintza->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($baldintza);
            $editForm = $this->createForm(BaldintzaType::class, $baldintza);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($baldintza);
                $this->em->flush();

                return $this->redirectToRoute('baldintza_edit', ['id' => $baldintza->getId()]);
            }

            return $this->render('baldintza/edit.html.twig', ['baldintza' => $baldintza, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Baldintza entity.
     *
     * @Route("/{id}", name="baldintza_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Baldintza $baldintza): RedirectResponse
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($baldintza->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($baldintza);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($baldintza);
                $this->em->flush();
            }
            return $this->redirectToRoute('baldintza_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a form to delete a Baldintza entity.
     *
     * @param Baldintza $baldintza The Baldintza entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Baldintza $baldintza)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('baldintza_delete', ['id' => $baldintza->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
