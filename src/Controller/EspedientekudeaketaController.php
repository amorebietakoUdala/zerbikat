<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Espedientekudeaketa;
use App\Form\EspedientekudeaketaType;
use App\Repository\EspedientekudeaketaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Espedientekudeaketa controller.
 */
#[Route(path: '/{_locale}/espedientekudeaketa')]
class EspedientekudeaketaController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em, 
        private EspedientekudeaketaRepository $repo
    )
    {
    }

    /**
     * Lists all Espedientekudeaketa entities.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/', defaults: ['page' => 1], name: 'espedientekudeaketa_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'espedientekudeaketa_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $espedientekudeaketas = $this->repo->findAll();

        $adapter = new ArrayAdapter($espedientekudeaketas);
        $pagerfanta = new Pagerfanta($adapter);

        $deleteForms = [];
        foreach ($espedientekudeaketas as $espedientekudeaketa) {
            $deleteForms[$espedientekudeaketa->getId()] = $this->createDeleteForm($espedientekudeaketa)->createView();
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

        return $this->render('espedientekudeaketa/index.html.twig', ['espedientekudeaketas' => $entities, 'deleteforms' => $deleteForms, 'pager' => $pagerfanta]);
    }

    /**
     * Creates a new Espedientekudeaketa entity.
     */
    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route(path: '/new', name: 'espedientekudeaketa_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $espedientekudeaketum = new Espedientekudeaketa();
        $form = $this->createForm(EspedientekudeaketaType::class, $espedientekudeaketum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($espedientekudeaketum);
            $this->em->flush();

            return $this->redirectToRoute('espedientekudeaketa_index');
        }

        return $this->render('espedientekudeaketa/new.html.twig', ['espedientekudeaketum' => $espedientekudeaketum, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Espedientekudeaketa entity.
     */
    #[Route(path: '/{id}', name: 'espedientekudeaketa_show', methods: ['GET'])]
    public function show(Espedientekudeaketa $espedientekudeaketum): Response
    {
        $deleteForm = $this->createDeleteForm($espedientekudeaketum);

        return $this->render('espedientekudeaketa/show.html.twig', ['espedientekudeaketum' => $espedientekudeaketum, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Espedientekudeaketa entity.
     */
    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route(path: '/{id}/edit', name: 'espedientekudeaketa_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Espedientekudeaketa $espedientekudeaketum)
    {
        $deleteForm = $this->createDeleteForm($espedientekudeaketum);
        $editForm = $this->createForm(EspedientekudeaketaType::class, $espedientekudeaketum);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em->persist($espedientekudeaketum);
            $this->em->flush();

            return $this->redirectToRoute('espedientekudeaketa_edit', ['id' => $espedientekudeaketum->getId()]);
        }

        return $this->render('espedientekudeaketa/edit.html.twig', ['espedientekudeaketum' => $espedientekudeaketum, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Deletes a Espedientekudeaketa entity.
     */
    #[IsGranted('ROLE_SUPER_ADMIN')]    
    #[Route(path: '/{id}', name: 'espedientekudeaketa_delete', methods: ['DELETE'])]
    public function delete(Request $request, Espedientekudeaketa $espedientekudeaketum): RedirectResponse
    {
        $form = $this->createDeleteForm($espedientekudeaketum);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->remove($espedientekudeaketum);
            $this->em->flush();
        }
        return $this->redirectToRoute('espedientekudeaketa_index');
    }

    /**
     * Creates a form to delete a Espedientekudeaketa entity.
     *
     * @param Espedientekudeaketa $espedientekudeaketum The Espedientekudeaketa entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Espedientekudeaketa $espedientekudeaketum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('espedientekudeaketa_delete', ['id' => $espedientekudeaketum->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
