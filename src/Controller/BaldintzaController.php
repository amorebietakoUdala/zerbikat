<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Baldintza;
use App\Form\BaldintzaType;
use App\Repository\BaldintzaRepository;
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
 * Baldintza controller.
 */
#[Route(path: '/{_locale}/baldintza')]
class BaldintzaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private BaldintzaRepository $repo
    )
    {
    }

    /**
     * Lists all Baldintza entities.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/', defaults: ['page' => 1], name: 'baldintza_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'baldintza_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $baldintzas = $this->repo->findAll();

        $deleteForms = [];
        foreach ($baldintzas as $baldintza) {
            $deleteForms[$baldintza->getId()] = $this->createDeleteForm($baldintza)->createView();
        }

        return $this->render('baldintza/index.html.twig', ['baldintzas' => $baldintzas, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Baldintza entity.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/new', name: 'baldintza_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $baldintza = new Baldintza();
        $form = $this->createForm(BaldintzaType::class, $baldintza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $baldintza = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $baldintza->setUdala($udala);                
            $this->em->persist($baldintza);
            $this->em->flush();

            return $this->redirectToRoute('baldintza_index');

        }

        return $this->render('baldintza/new.html.twig', ['baldintza' => $baldintza, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Baldintza entity.
     */
    #[Route(path: '/{id}', name: 'baldintza_show', methods: ['GET'])]
    public function show(Baldintza $baldintza): Response
    {
        $deleteForm = $this->createDeleteForm($baldintza);

        return $this->render('baldintza/show.html.twig', ['baldintza' => $baldintza, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Baldintza entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'baldintza_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Baldintza $baldintza)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($baldintza->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($baldintza);
            $editForm = $this->createForm(BaldintzaType::class, $baldintza);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($baldintza);
                $this->em->flush();

                return $this->redirectToRoute('baldintza_edit', ['id' => $baldintza->getId()]);
            }

            return $this->render('baldintza/edit.html.twig', ['baldintza' => $baldintza, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Baldintza entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'baldintza_delete', methods: ['DELETE'])]
    public function delete(Request $request, Baldintza $baldintza): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($baldintza->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($baldintza);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($baldintza);
                $this->em->flush();
            }
            return $this->redirectToRoute('baldintza_index');
        }else
        {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Creates a form to delete a Baldintza entity.
     *
     * @param Baldintza $baldintza The Baldintza entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Baldintza $baldintza)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('baldintza_delete', ['id' => $baldintza->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
