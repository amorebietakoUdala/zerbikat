<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Araudia;
use App\Form\AraudiaType;
use App\Repository\AraudiaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Araudia controller.
 *
 * @Route("/{_locale}/araudia")
 */
class AraudiaController extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, AraudiaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }
    /**
     * Lists all Araudia entities.
     *
     * @Route("/", defaults={"page"=1}, name="araudia_index", methods={"GET"})
     * @Route("/page{page}", name="araudia_index_paginated", methods={"GET"})
     */
    public function index($page)
    {
        if ($this->isGranted('ROLE_KUDEAKETA'))
        {
            $araudias = $this->repo->findBy( [], ['kodea'=>'ASC'] );

            $deleteForms = [];
            foreach ($araudias as $araudia) {
                $deleteForms[$araudia->getId()] = $this->createDeleteForm($araudia)->createView();
            }

            return $this->render('araudia/index.html.twig', ['araudias' => $araudias, 'deleteforms' => $deleteForms]);
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }


    /**
     * Creates a new Araudia entity.
     *
     * @Route("/new", name="araudia_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $araudium = new Araudia();
            $form = $this->createForm(AraudiaType::class, $araudium);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($araudium);
                $this->em->flush();

                return $this->redirectToRoute('araudia_index');
            }else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }
            

            return $this->render('araudia/new.html.twig', ['araudium' => $araudium, 'form' => $form->createView()]);
        }else
        {
            //Baimenik ez
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Araudia entity.
     *
     * @Route("/{id}", name="araudia_show", methods={"GET"})
     */
    public function show(Araudia $araudium): Response
    {
        $deleteForm = $this->createDeleteForm($araudium);

        return $this->render('araudia/show.html.twig', ['araudium' => $araudium, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Araudia entity.
     *
     * @Route("/{id}/edit", name="araudia_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Araudia $araudium)
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($araudium->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($araudium);
            $editForm = $this->createForm(AraudiaType::class, $araudium);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($araudium);
                $this->em->flush();

                return $this->redirectToRoute('araudia_edit', ['id' => $araudium->getId()]);
            }

            return $this->render('araudia/edit.html.twig', ['araudium' => $araudium, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Araudia entity.
     *
     * @Route("/{id}", name="araudia_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Araudia $araudium): RedirectResponse
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($araudium->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($araudium);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($araudium);
                $this->em->flush();
            }
            return $this->redirectToRoute('araudia_index');
        }else
        {
            //baimenik ez
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a form to delete a Araudia entity.
     *
     * @param Araudia $araudium The Araudia entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Araudia $araudium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('araudia_delete', ['id' => $araudium->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
