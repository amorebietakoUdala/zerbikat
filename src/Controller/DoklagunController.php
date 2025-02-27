<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Doklagun;
use App\Form\DoklagunType;
use App\Repository\DoklagunRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * Doklagun controller.
 */
#[Route(path: '/{_locale}/doklagun')]
class DoklagunController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private DoklagunRepository $repo
    )
    {
    }

    /**
     * Lists all Doklagun entities.
     */
    #[IsGranted("ROLE_KUDEAKETA")]
    #[Route(path: '/', defaults: ['page' => 1], name: 'doklagun_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'doklagun_index_paginated', methods: ['GET'])]
    public function index($page)
    {

        $doklaguns = $this->repo->findBy( [], ['kodea'=>'ASC'] );

        $deleteForms = [];
        foreach ($doklaguns as $doklagun) {
            $deleteForms[$doklagun->getId()] = $this->createDeleteForm($doklagun)->createView();
        }

        return $this->render('doklagun/index.html.twig', ['doklaguns' => $doklaguns, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Doklagun entity.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/new', name: 'doklagun_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {

        $doklagun = new Doklagun();
        $form = $this->createForm(DoklagunType::class, $doklagun);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doklagun = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $doklagun->setUdala($udala);
            $this->em->persist($doklagun);
            $this->em->flush();

            return $this->redirectToRoute('doklagun_index');
        }

        return $this->render('doklagun/new.html.twig', ['doklagun' => $doklagun, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Doklagun entity.
     */
    #[Route(path: '/{id}', name: 'doklagun_show', methods: ['GET'])]
    public function show(Doklagun $doklagun): Response
    {
        $deleteForm = $this->createDeleteForm($doklagun);

        return $this->render('doklagun/show.html.twig', ['doklagun' => $doklagun, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Doklagun entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'doklagun_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Doklagun $doklagun)
    {

        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($doklagun->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($doklagun);
            $editForm = $this->createForm(DoklagunType::class, $doklagun);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($doklagun);
                $this->em->flush();

                return $this->redirectToRoute('doklagun_edit', ['id' => $doklagun->getId()]);
            }

            return $this->render('doklagun/edit.html.twig', ['doklagun' => $doklagun, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Doklagun entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'doklagun_delete', methods: ['DELETE'])]
    public function delete(Request $request, Doklagun $doklagun): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($doklagun->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($doklagun);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($doklagun);
                $this->em->flush();
            }

            return $this->redirectToRoute('doklagun_index');
        }else
        {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Creates a form to delete a Doklagun entity.
     *
     * @param Doklagun $doklagun The Doklagun entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Doklagun $doklagun)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('doklagun_delete', ['id' => $doklagun->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
