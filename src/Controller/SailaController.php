<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Saila;
use App\Form\SailaType;
use App\Repository\SailaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Saila controller.
 *
 * @Route("/{_locale}/saila")
 */
class SailaController extends AbstractController
{
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, SailaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Saila entities.
     *
     * @Route("/", defaults={"page"=1}, name="saila_index", methods={"GET"})
     * @Route("/page{page}", name="saila_index_paginated", methods={"GET"})
     */
    public function index($page)
    {

        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $sailas = $this->repo->findBy( [], ['kodea'=>'ASC'] );

            $deleteForms = [];
            foreach ($sailas as $saila) {
                $deleteForms[$saila->getId()] = $this->createDeleteForm($saila)->createView();
            }

            return $this->render('saila/index.html.twig', ['sailas' => $sailas, 'deleteforms' => $deleteForms]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Saila entity.
     *
     * @Route("/new", name="saila_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN'))
        {
            $saila = new Saila();
            $form = $this->createForm(SailaType::class, $saila);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($saila);
                $this->em->flush();

//                return $this->redirectToRoute('saila_show', array('id' => $saila->getId()));
                return $this->redirectToRoute('saila_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('saila/new.html.twig', ['saila' => $saila, 'form' => $form->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Saila entity.
     *
     * @Route("/{id}", name="saila_show", methods={"GET"})
     */
    public function show(Saila $saila): Response
    {
        $deleteForm = $this->createDeleteForm($saila);

        return $this->render('saila/show.html.twig', ['saila' => $saila, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Saila entity.
     *
     * @Route("/{id}/edit", name="saila_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Saila $saila)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($saila->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($saila);
            $editForm = $this->createForm(SailaType::class, $saila);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($saila);
                $this->em->flush();
    
                return $this->redirectToRoute('saila_edit', ['id' => $saila->getId()]);
            }
    
            return $this->render('saila/edit.html.twig', ['saila' => $saila, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Deletes a Saila entity.
     *
     * @Route("/{id}", name="saila_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Saila $saila): RedirectResponse
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($saila->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($saila);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($saila);
                $this->em->flush();
            }
            return $this->redirectToRoute('saila_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Creates a form to delete a Saila entity.
     *
     * @param Saila $saila The Saila entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Saila $saila)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('saila_delete', ['id' => $saila->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
