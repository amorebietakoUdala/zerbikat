<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Norkeskatu;
use App\Form\NorkeskatuType;
use App\Repository\NorkeskatuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Norkeskatu controller.
 */
#[Route(path: '/{_locale}/norkeskatu')]
class NorkeskatuController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private NorkeskatuRepository $repo
    )
    {
    }

    /**
     * Lists all Norkeskatu entities.
     */
    #[IsGranted('ROLE_KUDEAKETA')]
    #[Route(path: '/', defaults: ['page' => 1], name: 'norkeskatu_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'norkeskatu_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $norkeskatus = $this->repo->findAll();

        $deleteForms = [];
        foreach ($norkeskatus as $norkeskatu) {
            $deleteForms[$norkeskatu->getId()] = $this->createDeleteForm($norkeskatu)->createView();
        }

        return $this->render('norkeskatu/index.html.twig', ['norkeskatus' => $norkeskatus, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Norkeskatu entity.
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/new', name: 'norkeskatu_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $norkeskatu = new Norkeskatu();
        $form = $this->createForm(NorkeskatuType::class, $norkeskatu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $norkeskatu = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $norkeskatu->setUdala($udala);                
            $this->em->persist($norkeskatu);
            $this->em->flush();

            return $this->redirectToRoute('norkeskatu_index');
        }

        return $this->render('norkeskatu/new.html.twig', ['norkeskatu' => $norkeskatu, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Norkeskatu entity.
     */
    #[Route(path: '/{id}', name: 'norkeskatu_show', methods: ['GET'])]
    public function show(Norkeskatu $norkeskatu): Response
    {
        $deleteForm = $this->createDeleteForm($norkeskatu);

        return $this->render('norkeskatu/show.html.twig', ['norkeskatu' => $norkeskatu, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Norkeskatu entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'norkeskatu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Norkeskatu $norkeskatu)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($norkeskatu->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');
        }            
    }

    /**
     * Deletes a Norkeskatu entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'norkeskatu_delete', methods: ['DELETE'])]
    public function delete(Request $request, Norkeskatu $norkeskatu): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($norkeskatu->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');
        }            
    }

    /**
     * Creates a form to delete a Norkeskatu entity.
     *
     * @param Norkeskatu $norkeskatu The Norkeskatu entity
     *
     * @return Form The form
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
