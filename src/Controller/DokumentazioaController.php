<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Dokumentazioa;
use App\Form\DokumentazioaType;
use App\Repository\DokumentazioaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Dokumentazioa controller.
 *
 * @Route("/{_locale}/dokumentazioa")
 */
class DokumentazioaController extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, DokumentazioaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Dokumentazioa entities.
     *
     * @Route("/", defaults={"page" = 1}, name="dokumentazioa_index")
     * @Route("/page{page}", name="dokumentazioa_index_paginated")
     * @Method("GET")
     */
    public function index($page)
    {

        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $dokumentazioas = $this->repo->findBy( [], ['kodea'=>'ASC'] );

            $deleteForms = [];
            foreach ($dokumentazioas as $dokumentazioa) {
                $deleteForms[$dokumentazioa->getId()] = $this->createDeleteForm($dokumentazioa)->createView();
            }

            return $this->render('dokumentazioa/index.html.twig', ['dokumentazioas' => $dokumentazioas, 'deleteforms' => $deleteForms]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Dokumentazioa entity.
     *
     * @Route("/new", name="dokumentazioa_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN')) 
        {
            $dokumentazioa = new Dokumentazioa();
            $form = $this->createForm(DokumentazioaType::class, $dokumentazioa);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($dokumentazioa);
                $this->em->flush();
    
//                return $this->redirectToRoute('dokumentazioa_show', array('id' => $dokumentazioa->getId()));
                return $this->redirectToRoute('dokumentazioa_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }
    
            return $this->render('dokumentazioa/new.html.twig', ['dokumentazioa' => $dokumentazioa, 'form' => $form->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Dokumentazioa entity.
     *
     * @Route("/{id}", name="dokumentazioa_show")
     * @Method("GET")
     */
    public function show(Dokumentazioa $dokumentazioa): Response
    {
        $deleteForm = $this->createDeleteForm($dokumentazioa);

        return $this->render('dokumentazioa/show.html.twig', ['dokumentazioa' => $dokumentazioa, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Dokumentazioa entity.
     *
     * @Route("/{id}/edit", name="dokumentazioa_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Dokumentazioa $dokumentazioa)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($dokumentazioa->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($dokumentazioa);
            $editForm = $this->createForm(DokumentazioaType::class, $dokumentazioa);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($dokumentazioa);
                $this->em->flush();

                return $this->redirectToRoute('dokumentazioa_edit', ['id' => $dokumentazioa->getId()]);
            }

            return $this->render('dokumentazioa/edit.html.twig', ['dokumentazioa' => $dokumentazioa, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Dokumentazioa entity.
     *
     * @Route("/{id}", name="dokumentazioa_delete")
     * @Method("DELETE")
     */
    public function delete(Request $request, Dokumentazioa $dokumentazioa): RedirectResponse
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($dokumentazioa->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($dokumentazioa);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($dokumentazioa);
                $this->em->flush();
            }
            return $this->redirectToRoute('dokumentazioa_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a form to delete a Dokumentazioa entity.
     *
     * @param Dokumentazioa $dokumentazioa The Dokumentazioa entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Dokumentazioa $dokumentazioa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('dokumentazioa_delete', ['id' => $dokumentazioa->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
