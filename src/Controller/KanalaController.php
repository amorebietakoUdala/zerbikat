<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Kanala;
use App\Form\KanalaType;
use App\Repository\KanalaRepository;
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
 * Kanala controller.
 */
#[Route(path: '/{_locale}/kanala')]
class KanalaController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em, 
        private KanalaRepository $repo
    )
    {
    }

    /**
     * Lists all Kanala entities.
     */
    #[IsGranted('ROLE_KUDEAKETA')]    
    #[Route(path: '/', defaults: ['page' => 1], name: 'kanala_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'kanala_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $kanalas = $this->repo->findBy( [], ['kanalmota'=>'ASC'] );

        $deleteForms = [];
        foreach ($kanalas as $kanala) {
            $deleteForms[$kanala->getId()] = $this->createDeleteForm($kanala)->createView();
        }

        return $this->render('kanala/index.html.twig', ['kanalas' => $kanalas, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Kanala entity.
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/new', name: 'kanala_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $kanala = new Kanala();
        $form = $this->createForm(KanalaType::class, $kanala);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $kanala = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $kanala->setUdala($udala);
            $this->em->persist($kanala);
            $this->em->flush();

            return $this->redirectToRoute('kanala_index');
        }

        return $this->render('kanala/new.html.twig', ['kanala' => $kanala, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Kanala entity.
     */
    #[Route(path: '/{id}', name: 'kanala_show', methods: ['GET'])]
    public function show(Kanala $kanala): Response
    {
        $deleteForm = $this->createDeleteForm($kanala);

        return $this->render('kanala/show.html.twig', ['kanala' => $kanala, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Kanala entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]    
    #[Route(path: '/{id}/edit', name: 'kanala_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Kanala $kanala)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($kanala->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Kanala entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'kanala_delete', methods: ['DELETE'])]
    public function delete(Request $request, Kanala $kanala): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($kanala->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');
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
