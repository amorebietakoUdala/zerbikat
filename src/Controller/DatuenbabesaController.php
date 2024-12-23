<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Datuenbabesa;
use App\Form\DatuenbabesaType;
use App\Repository\DatuenbabesaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Datuenbabesa controller.
 *
 * @Route("/{_locale}/datuenbabesa")
 */
class DatuenbabesaController extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, DatuenbabesaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Datuenbabesa entities.
     *
     * @Route("/", defaults={"page"=1}, name="datuenbabesa_index", methods={"GET"})
     * @Route("/page{page}", name="datuenbabesa_index_paginated", methods={"GET"})
     */
    public function index($page)
    {

        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $datuenbabesas = $this->repo->findBy( [], ['kodea'=>'ASC'] );

            $deleteForms = [];
            foreach ($datuenbabesas as $datuenbabesa) {
                $deleteForms[$datuenbabesa->getId()] = $this->createDeleteForm($datuenbabesa)->createView();
            }

            return $this->render('datuenbabesa/index.html.twig', ['datuenbabesas' => $datuenbabesas, 'deleteforms' => $deleteForms]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Datuenbabesa entity.
     *
     * @Route("/new", name="datuenbabesa_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN')) 
        {
            $datuenbabesa = new Datuenbabesa();
            $form = $this->createForm(DatuenbabesaType::class, $datuenbabesa);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());
            
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($datuenbabesa);
                $this->em->flush();
    
//                return $this->redirectToRoute('datuenbabesa_show', array('id' => $datuenbabesa->getId()));
                return $this->redirectToRoute('datuenbabesa_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }
    
            return $this->render('datuenbabesa/new.html.twig', ['datuenbabesa' => $datuenbabesa, 'form' => $form->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Datuenbabesa entity.
     *
     * @Route("/{id}", name="datuenbabesa_show", methods={"GET"})
     */
    public function show(Datuenbabesa $datuenbabesa): Response
    {
        $deleteForm = $this->createDeleteForm($datuenbabesa);

        return $this->render('datuenbabesa/show.html.twig', ['datuenbabesa' => $datuenbabesa, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Datuenbabesa entity.
     *
     * @Route("/{id}/edit", name="datuenbabesa_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Datuenbabesa $datuenbabesa)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($datuenbabesa->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($datuenbabesa);
            $editForm = $this->createForm(DatuenbabesaType::class, $datuenbabesa);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($datuenbabesa);
                $this->em->flush();
    
                return $this->redirectToRoute('datuenbabesa_edit', ['id' => $datuenbabesa->getId()]);
            }
    
            return $this->render('datuenbabesa/edit.html.twig', ['datuenbabesa' => $datuenbabesa, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Deletes a Datuenbabesa entity.
     *
     * @Route("/{id}", name="datuenbabesa_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Datuenbabesa $datuenbabesa): RedirectResponse
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($datuenbabesa->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($datuenbabesa);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($datuenbabesa);
                $this->em->flush();
            }
            return $this->redirectToRoute('datuenbabesa_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a form to delete a Datuenbabesa entity.
     *
     * @param Datuenbabesa $datuenbabesa The Datuenbabesa entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Datuenbabesa $datuenbabesa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('datuenbabesa_delete', ['id' => $datuenbabesa->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
