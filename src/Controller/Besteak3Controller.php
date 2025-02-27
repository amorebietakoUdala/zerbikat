<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Besteak3;
use App\Form\Besteak3Type;
use App\Repository\Besteak3Repository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * Besteak3 controller.
 */
#[Route(path: '/{_locale}/besteak3')]
class Besteak3Controller extends AbstractController
{

    public function __construct(private EntityManagerInterface $em, private Besteak3Repository $repo)
    {
    }

    /**
     * Lists all Besteak3 entities.
     */
    #[IsGranted("ROLE_KUDEAKETA")]
    #[Route(path: '/', defaults: ['page' => 1], name: 'besteak3_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'besteak3_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $besteak3s = $this->repo->findBy( [], ['kodea'=>'ASC'] );

        $deleteForms = [];
        foreach ($besteak3s as $besteak3) {
            $deleteForms[$besteak3->getId()] = $this->createDeleteForm($besteak3)->createView();
        }

        return $this->render('besteak3/index.html.twig', ['besteak3s' => $besteak3s, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Besteak3 entity.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/new', name: 'besteak3_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $besteak3 = new Besteak3();
        $form = $this->createForm(Besteak3Type::class, $besteak3);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $besteak3 = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $besteak3->setUdala($udala);
            $this->em->persist($besteak3);
            $this->em->flush();

            return $this->redirectToRoute('besteak3_index');
        }

        return $this->render('besteak3/new.html.twig', ['besteak3' => $besteak3, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Besteak3 entity.
     */
    #[Route(path: '/{id}', name: 'besteak3_show', methods: ['GET'])]
    public function show(Besteak3 $besteak3): \Symfony\Component\HttpFoundation\Response
    {
        $deleteForm = $this->createDeleteForm($besteak3);

        return $this->render('besteak3/show.html.twig', ['besteak3' => $besteak3, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Besteak3 entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'besteak3_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Besteak3 $besteak3)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($besteak3->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($besteak3);
            $editForm = $this->createForm(Besteak3Type::class, $besteak3);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($besteak3);
                $this->em->flush();

                return $this->redirectToRoute('besteak3_edit', ['id' => $besteak3->getId()]);
            }

            return $this->render('besteak3/edit.html.twig', ['besteak3' => $besteak3, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Besteak3 entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'besteak3_delete', methods: ['DELETE'])]
    public function delete(Request $request, Besteak3 $besteak3): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($besteak3->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($besteak3);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($besteak3);
                $this->em->flush();
            }
            return $this->redirectToRoute('besteak3_index');
        }else
        {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Creates a form to delete a Besteak3 entity.
     *
     * @param Besteak3 $besteak3 The Besteak3 entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Besteak3 $besteak3)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('besteak3_delete', ['id' => $besteak3->getId()]))
            ->setMethod(\Symfony\Component\HttpFoundation\Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
