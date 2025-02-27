<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Saila;
use App\Form\SailaType;
use App\Repository\SailaRepository;
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
 * Saila controller.
 */
#[Route(path: '/{_locale}/saila')]
class SailaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private SailaRepository $repo
    )
    {
    }

    /**
     * Lists all Saila entities.
     */
    #[IsGranted('ROLE_KUDEAKETA')]
    #[Route(path: '/', defaults: ['page' => 1], name: 'saila_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'saila_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $sailas = $this->repo->findBy( [], ['kodea'=>'ASC'] );

        $deleteForms = [];
        foreach ($sailas as $saila) {
            $deleteForms[$saila->getId()] = $this->createDeleteForm($saila)->createView();
        }

        return $this->render('saila/index.html.twig', ['sailas' => $sailas, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Saila entity.
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/new', name: 'saila_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $saila = new Saila();
        $form = $this->createForm(SailaType::class, $saila);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $saila = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $saila->setUdala($udala);
            $this->em->persist($saila);
            $this->em->flush();

            return $this->redirectToRoute('saila_index');
        }

        return $this->render('saila/new.html.twig', ['saila' => $saila, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Saila entity.
     */
    #[Route(path: '/{id}', name: 'saila_show', methods: ['GET'])]
    public function show(Saila $saila): Response
    {
        $deleteForm = $this->createDeleteForm($saila);

        return $this->render('saila/show.html.twig', ['saila' => $saila, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Saila entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'saila_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Saila $saila)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($saila->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');
        }            
    }

    /**
     * Deletes a Saila entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'saila_delete', methods: ['DELETE'])]
    public function delete(Request $request, Saila $saila): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($saila->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');
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
