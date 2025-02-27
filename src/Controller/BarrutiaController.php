<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Barrutia;
use App\Form\BarrutiaType;
use App\Repository\BarrutiaRepository;
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
 * Barrutia controller.
 */
#[Route(path: '/{_locale}/barrutia')]
class BarrutiaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private BarrutiaRepository $repo
    )
    {
    }

    /**
     * Lists all Barrutia entities.
     */
    #[IsGranted("ROLE_KUDEAKETA")]
    #[Route(path: '/', defaults: ['page' => 1], name: 'barrutia_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'barrutia_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $barrutias = $this->repo->findAll();

        $adapter = new ArrayAdapter($barrutias);
        $pagerfanta = new Pagerfanta($adapter);

        $deleteForms = [];
        foreach ($barrutias as $barrutia) {
            $deleteForms[$barrutia->getId()] = $this->createDeleteForm($barrutia)->createView();
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

        return $this->render('barrutia/index.html.twig', ['barrutias' => $entities, 'deleteforms' => $deleteForms, 'pager' => $pagerfanta]);
    }

    /**
     * Creates a new Barrutia entity.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/new', name: 'barrutia_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $barrutium = new Barrutia();
        $form = $this->createForm(BarrutiaType::class, $barrutium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $barrutium = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $barrutium->setUdala($udala);
            $this->em->persist($barrutium);
            $this->em->flush();

            return $this->redirectToRoute('barrutia_index');
        }

        return $this->render('barrutia/new.html.twig', ['barrutium' => $barrutium, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Barrutia entity.
     */
    #[Route(path: '/{id}', name: 'barrutia_show', methods: ['GET'])]
    public function show(Barrutia $barrutium): Response
    {
        $deleteForm = $this->createDeleteForm($barrutium);

        return $this->render('barrutia/show.html.twig', ['barrutium' => $barrutium, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Barrutia entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'barrutia_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Barrutia $barrutium)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($barrutium->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($barrutium);
            $editForm = $this->createForm(BarrutiaType::class, $barrutium);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($barrutium);
                $this->em->flush();

                return $this->redirectToRoute('barrutia_edit', ['id' => $barrutium->getId()]);
            }

            return $this->render('barrutia/edit.html.twig', ['barrutium' => $barrutium, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Barrutia entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'barrutia_delete', methods: ['DELETE'])]
    public function delete(Request $request, Barrutia $barrutium): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($barrutium->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($barrutium);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($barrutium);
                $this->em->flush();
            }
            return $this->redirectToRoute('barrutia_index');
        }else
        {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }            
    }

    /**
     * Creates a form to delete a Barrutia entity.
     *
     * @param Barrutia $barrutium The Barrutia entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Barrutia $barrutium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('barrutia_delete', ['id' => $barrutium->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
