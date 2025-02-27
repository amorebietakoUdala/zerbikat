<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Zerbitzua;
use App\Form\ZerbitzuaType;
use App\Repository\ZerbitzuaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Zerbitzua controller.
 */
#[Route(path: '/{_locale}/zerbitzua')]
class ZerbitzuaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private ZerbitzuaRepository $repo
    )
    {
    }

    /**
     * Lists all Zerbitzua entities.
     */
    #[IsGranted("ROLE_SUPER_ADMIN")]
    #[Route(path: '/', defaults: ['page' => 1], name: 'zerbitzua_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'zerbitzua_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $zerbitzuas = $this->repo->findAll();

        $adapter = new ArrayAdapter($zerbitzuas);
        $pagerfanta = new Pagerfanta($adapter);

        $deleteForms = [];
        foreach ($zerbitzuas as $zerbitzua) {
            $deleteForms[$zerbitzua->getId()] = $this->createDeleteForm($zerbitzua)->createView();
        }

        try {
            $entities = $pagerfanta
                // Le nombre maximum d'éléments par page
//                    ->setMaxPerPage($this->getUser()->getUdala()->getOrrikatzea())
                // Notre position actuelle (numéro de page)
                ->setCurrentPage($page)
                // On récupère nos entités via Pagerfanta,
                // celui-ci s'occupe de limiter la requête en fonction de nos réglages.
                ->getCurrentPageResults()
            ;
        } catch (NotValidCurrentPageException) {
            throw $this->createNotFoundException("Orria ez da existitzen");
        }
        
        return $this->render('zerbitzua/index.html.twig', ['zerbitzuas' => $entities, 'deleteforms' => $deleteForms, 'pager' => $pagerfanta]);
    }

    /**
     * Creates a new Zerbitzua entity.
     */
    #[IsGranted("ROLE_SUPER_ADMIN")]
    #[Route(path: '/new', name: 'zerbitzua_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $zerbitzua = new Zerbitzua();
        $form = $this->createForm(ZerbitzuaType::class, $zerbitzua);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($zerbitzua);
            $this->em->flush();

            return $this->redirectToRoute('zerbitzua_index');
        }
        return $this->render('zerbitzua/new.html.twig', ['zerbitzua' => $zerbitzua, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Zerbitzua entity.
     */
    #[Route(path: '/{id}', name: 'zerbitzua_show', methods: ['GET'])]
    public function show(Zerbitzua $zerbitzua): Response
    {
        $deleteForm = $this->createDeleteForm($zerbitzua);

        return $this->render('zerbitzua/show.html.twig', ['zerbitzua' => $zerbitzua, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Zerbitzua entity.
     */
    #[IsGranted("ROLE_SUPER_ADMIN")]
    #[Route(path: '/{id}/edit', name: 'zerbitzua_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Zerbitzua $zerbitzua)
    {
        $deleteForm = $this->createDeleteForm($zerbitzua);
        $editForm = $this->createForm(ZerbitzuaType::class, $zerbitzua);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em->persist($zerbitzua);
            $this->em->flush();

            return $this->redirectToRoute('zerbitzua_edit', ['id' => $zerbitzua->getId()]);
        }

        return $this->render('zerbitzua/edit.html.twig', ['zerbitzua' => $zerbitzua, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Deletes a Zerbitzua entity.
     */
    #[IsGranted("ROLE_SUPER_ADMIN")]
    #[Route(path: '/{id}', name: 'zerbitzua_delete', methods: ['DELETE'])]
    public function delete(Request $request, Zerbitzua $zerbitzua): RedirectResponse
    {
        $form = $this->createDeleteForm($zerbitzua);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->remove($zerbitzua);
            $this->em->flush();
        }
        return $this->redirectToRoute('zerbitzua_index');
    }

    /**
     * Creates a form to delete a Zerbitzua entity.
     *
     * @param Zerbitzua $zerbitzua The Zerbitzua entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Zerbitzua $zerbitzua)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('zerbitzua_delete', ['id' => $zerbitzua->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
