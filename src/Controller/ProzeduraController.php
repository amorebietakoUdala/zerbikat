<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Prozedura;
use App\Form\ProzeduraType;
use App\Repository\ProzeduraRepository;
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
 * Prozedura controller.
 */
#[Route(path: '/{_locale}/prozedura')]
class ProzeduraController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private ProzeduraRepository $repo
    )
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Prozedura entities.
     */
    #[IsGranted('ROLE_KUDEAKETA')]
    #[Route(path: '/', defaults: ['page' => 1], name: 'prozedura_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'prozedura_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $prozeduras = $this->repo->findAll();

        $deleteForms = [];
        foreach ($prozeduras as $prozedura) {
            $deleteForms[$prozedura->getId()] = $this->createDeleteForm($prozedura)->createView();
        }

        return $this->render('prozedura/index.html.twig', ['prozeduras' => $prozeduras, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Prozedura entity.
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/new', name: 'prozedura_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $prozedura = new Prozedura();
        $form = $this->createForm(ProzeduraType::class, $prozedura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prozedura = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $prozedura->setUdala($udala);               
            $this->em->persist($prozedura);
            $this->em->flush();

            return $this->redirectToRoute('prozedura_index');
        }

        return $this->render('prozedura/new.html.twig', ['prozedura' => $prozedura, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Prozedura entity.
     */
    #[Route(path: '/{id}', name: 'prozedura_show', methods: ['GET'])]
    public function show(Prozedura $prozedura): Response
    {
        $deleteForm = $this->createDeleteForm($prozedura);

        return $this->render('prozedura/show.html.twig', ['prozedura' => $prozedura, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Prozedura entity.
     */
    #[Route(path: '/{id}/edit', name: 'prozedura_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Prozedura $prozedura)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($prozedura->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Prozedura entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'prozedura_delete', methods: ['DELETE'])]
    public function delete(Request $request, Prozedura $prozedura): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($prozedura->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');
        }            
    }

    /**
     * Creates a form to delete a Prozedura entity.
     *
     * @param Prozedura $prozedura The Prozedura entity
     *
     * @return Form The form
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
