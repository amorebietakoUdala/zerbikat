<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Kanalmota;
use App\Form\KanalmotaType;
use App\Repository\KanalmotaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Kanalmota controller.
 */
#[Route(path: '/{_locale}/kanalmota')]
class KanalmotaController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em, 
        private KanalmotaRepository $repo
    )
    {
    }

    /**
     * Lists all Kanalmota entities.
     */
    #[IsGranted('ROLE_KUDEAKETA')]
    #[Route(path: '/', defaults: ['page' => 1], name: 'kanalmota_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'kanalmota_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $kanalmotas = $this->repo->findAll();

        $adapter = new ArrayAdapter($kanalmotas);
        $pagerfanta = new Pagerfanta($adapter);

        $deleteForms = [];
        foreach ($kanalmotas as $kanalmota) {
            $deleteForms[$kanalmota->getId()] = $this->createDeleteForm($kanalmota)->createView();
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

        return $this->render('kanalmota/index.html.twig', ['kanalmotas' => $entities, 'deleteforms' => $deleteForms, 'pager' => $pagerfanta]);
    }

    /**
     * Creates a new Kanalmota entity.
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/new', name: 'kanalmota_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $kanalmotum = new Kanalmota();
        $form = $this->createForm(KanalmotaType::class, $kanalmotum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $kanalmotum = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $kanalmotum->setUdala($user->getUdala());
            $this->em->persist($kanalmotum);
            $this->em->flush();

//                return $this->redirectToRoute('kanalmota_show', array('id' => $kanalmotum->getId()));
            return $this->redirectToRoute('kanalmota_index');
        }

        return $this->render('kanalmota/new.html.twig', ['kanalmotum' => $kanalmotum, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Kanalmota entity.
     */
    #[Route(path: '/{id}', name: 'kanalmota_show', methods: ['GET'])]
    public function show(Kanalmota $kanalmotum): Response
    {
        $deleteForm = $this->createDeleteForm($kanalmotum);

        return $this->render('kanalmota/show.html.twig', ['kanalmotum' => $kanalmotum, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Kanalmota entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]    
    #[Route(path: '/{id}/edit', name: 'kanalmota_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Kanalmota $kanalmotum)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($kanalmotum->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($kanalmotum);
            $editForm = $this->createForm(KanalmotaType::class, $kanalmotum);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($kanalmotum);
                $this->em->flush();

                return $this->redirectToRoute('kanalmota_edit', ['id' => $kanalmotum->getId()]);
            }

            return $this->render('kanalmota/edit.html.twig', ['kanalmotum' => $kanalmotum, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Kanalmota entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'kanalmota_delete', methods: ['DELETE'])]
    public function delete(Request $request, Kanalmota $kanalmotum): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($kanalmotum->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($kanalmotum);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($kanalmotum);
                $this->em->flush();
            }
            return $this->redirectToRoute('kanalmota_index');
        }else
        {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Creates a form to delete a Kanalmota entity.
     *
     * @param Kanalmota $kanalmotum The Kanalmota entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Kanalmota $kanalmotum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('kanalmota_delete', ['id' => $kanalmotum->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
