<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Azpisaila;
use App\Form\AzpisailaType;
use App\Repository\AzpisailaRepository;
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
 * Azpisaila controller.
 */
#[Route(path: '/{_locale}/azpisaila')]
class AzpisailaController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em, 
        private AzpisailaRepository $repo
    )
    {
    }

    /**
     * Lists all Azpisaila entities.
     */
    #[IsGranted("ROLE_KUDEAKETA")]
    #[Route(path: '/', defaults: ['page' => 1], name: 'azpisaila_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'azpisaila_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $azpisailas = $this->repo->findBy( [], ['kodea'=>'ASC'] );

        $deleteForms = [];
        foreach ($azpisailas as $azpisaila) {
            $deleteForms[$azpisaila->getId()] = $this->createDeleteForm($azpisaila)->createView();
        }

        return $this->render('azpisaila/index.html.twig', ['azpisailas' => $azpisailas, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Azpisaila entity.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/new', name: 'azpisaila_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $azpisaila = new Azpisaila();
        $form = $this->createForm(AzpisailaType::class, $azpisaila);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $azpisaila = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $azpisaila->setUdala($udala);                
            $this->em->persist($azpisaila);
            $this->em->flush();

            return $this->redirectToRoute('azpisaila_index');
        }

        return $this->render('azpisaila/new.html.twig', ['azpisaila' => $azpisaila, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Azpisaila entity.
     */
    #[Route(path: '/{id}', name: 'azpisaila_show', methods: ['GET'])]
    public function show(Azpisaila $azpisaila): Response
    {
        $deleteForm = $this->createDeleteForm($azpisaila);

        return $this->render('azpisaila/show.html.twig', ['azpisaila' => $azpisaila, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Azpisaila entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'azpisaila_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Azpisaila $azpisaila)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($azpisaila->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($azpisaila);
            $editForm = $this->createForm(AzpisailaType::class, $azpisaila);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($azpisaila);
                $this->em->flush();

                return $this->redirectToRoute('azpisaila_edit', ['id' => $azpisaila->getId()]);
            }

            return $this->render('azpisaila/edit.html.twig', ['azpisaila' => $azpisaila, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Azpisaila entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'azpisaila_delete', methods: ['DELETE'])]
    public function delete(Request $request, Azpisaila $azpisaila): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($azpisaila->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($azpisaila);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($azpisaila);
                $this->em->flush();
            }
            return $this->redirectToRoute('azpisaila_index');
        }else
        {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Creates a form to delete a Azpisaila entity.
     *
     * @param Azpisaila $azpisaila The Azpisaila entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Azpisaila $azpisaila)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('azpisaila_delete', ['id' => $azpisaila->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
