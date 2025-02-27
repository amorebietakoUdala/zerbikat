<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Besteak1;
use App\Form\Besteak1Type;
use App\Repository\Besteak1Repository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\ExpressionLanguage\Expression;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Besteak1 controller.
 */
#[Route(path: '/{_locale}/besteak1')]
class Besteak1Controller extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private Besteak1Repository $repo
    )
    {
    }

    /**
     * Lists all Besteak1 entities.
     */
    #[IsGranted("ROLE_KUDEAKETA")]
    #[Route(path: '/', defaults: ['page' => 1], name: 'besteak1_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'besteak1_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $besteak1s = $this->repo->findBy( [], ['kodea'=>'ASC'] );
        
        $deleteForms = [];
        foreach ($besteak1s as $besteak1) {
            $deleteForms[$besteak1->getId()] = $this->createDeleteForm($besteak1)->createView();
        }

        return $this->render('besteak1/index.html.twig', ['besteak1s' => $besteak1s, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Besteak1 entity.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/new', name: 'besteak1_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $besteak1 = new Besteak1();
        $form = $this->createForm(Besteak1Type::class, $besteak1);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $besteak1 = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $besteak1->setUdala($udala);
            $this->em->persist($besteak1);
            $this->em->flush();

            return $this->redirectToRoute('besteak1_index');                
        }

        return $this->render('besteak1/new.html.twig', ['besteak1' => $besteak1, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Besteak1 entity.
     */
    #[Route(path: '/{id}', name: 'besteak1_show', methods: ['GET'])]
    public function show(Besteak1 $besteak1): Response
    {
        $deleteForm = $this->createDeleteForm($besteak1);

        return $this->render('besteak1/show.html.twig', ['besteak1' => $besteak1, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Besteak1 entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'besteak1_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Besteak1 $besteak1)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($besteak1->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($besteak1);
            $editForm = $this->createForm(Besteak1Type::class, $besteak1);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($besteak1);
                $this->em->flush();
    
                return $this->redirectToRoute('besteak1_edit', ['id' => $besteak1->getId()]);
            }
    
            return $this->render('besteak1/edit.html.twig', ['besteak1' => $besteak1, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');
        }            
    }

    /**
     * Deletes a Besteak1 entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'besteak1_delete', methods: ['DELETE'])]
    public function delete(Request $request, Besteak1 $besteak1): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($besteak1->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($besteak1);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($besteak1);
                $this->em->flush();
            }
            return $this->redirectToRoute('besteak1_index');
        }else
        {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }            
    }

    /**
     * Creates a form to delete a Besteak1 entity.
     *
     * @param Besteak1 $besteak1 The Besteak1 entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Besteak1 $besteak1)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('besteak1_delete', ['id' => $besteak1->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
