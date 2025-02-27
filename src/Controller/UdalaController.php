<?php

namespace App\Controller;

use Pagerfanta\Exception\NotValidCurrentPageException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Udala;
use App\Form\UdalaType;
use App\Repository\UdalaRepository;
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
 * Udala controller.
 */
#[Route(path: '/{_locale}/udala')]
class UdalaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private UdalaRepository $repo
    )
    {
    }

    /**
     * Lists all Udala entities.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/', defaults: ['page' => 1], name: 'udala_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'udala_index_paginated', methods: ['GET'])]
    public function index ( $page )
    {
        if ( $this->isGranted( 'ROLE_SUPER_ADMIN' ) ) {
            $udalas = $this->repo->findAll();

            $adapter = new ArrayAdapter( $udalas );
            $pagerfanta = new Pagerfanta( $adapter );

            $deleteForms = [];
            foreach ( $udalas as $udala ) {
                $deleteForms[$udala->getId()] = $this->createDeleteForm( $udala )->createView();
            }

            try {
                $entities = $pagerfanta
                    // Le nombre maximum d'éléments par page
//                        ->setMaxPerPage( $this->getUser()->getUdala()->getOrrikatzea() )
                    // Notre position actuelle (numéro de page)
                    ->setCurrentPage( $page )
                    // On récupère nos entités via Pagerfanta,
                    // celui-ci s'occupe de limiter la requête en fonction de nos réglages.
                    ->getCurrentPageResults();
            } catch ( NotValidCurrentPageException ) {
                throw $this->createNotFoundException( "Orria ez da existitzen" );
            }

            return $this->render(
                'udala/index.html.twig',
                ['udalas'      => $entities, 'deleteforms' => $deleteForms, 'pager'       => $pagerfanta]
            );
        } else {
            if ( $this->isGranted( 'ROLE_ADMIN' ) ) {
                // Begiratu ea erabiltzaileak Udala baduen
                /** @var User $user */
                $user = $this->getUser();
                if ($user->getUdala()) {
                    return $this->redirectToRoute(
                        'udala_show',
                        ['id' => $user->getUdala()->getId()]
                    );
                } else {
                    return $this->redirectToRoute(
                        'udala_ez'
                    );
                }

            } else {
                throw new AccessDeniedHttpException('Access Denied');
            }
        }
    }

    /**
     * Creates a new Udala entity.
     */
    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route(path: '/new', name: 'udala_new', methods: ['GET', 'POST'])]
    public function new ( Request $request )
    {
        $udala = new Udala();
        $form = $this->createForm( UdalaType::class, $udala );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $udala = $form->getData();
            $this->em->persist( $udala );
            $this->em->flush();

            return $this->redirectToRoute( 'udala_show', ['id' => $udala->getId()] );
        }

        return $this->render(
            'udala/new.html.twig',
            ['udala' => $udala, 'form'  => $form->createView()]
        );
    }

    /**
     * Finds and displays a Udala entity.
     */
    #[Route(path: '/{id}', name: 'udala_show', methods: ['GET'])]
    public function show ( Udala $udala ): Response
    {
        $deleteForm = $this->createDeleteForm( $udala );

        return $this->render(
            'udala/show.html.twig',
            ['udala'       => $udala, 'delete_form' => $deleteForm->createView()]
        );
    }

    /**
     * Displays a form to edit an existing Udala entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'udala_edit', methods: ['GET', 'POST'])]
    public function edit ( Request $request, Udala $udala )
    {
        /** @var User $user */
        $user = $this->getUser();
        if ( (($this->isGranted( 'ROLE_ADMIN' )) && ($udala == $user->getUdala()))
            || ($this->isGranted( 'ROLE_SUPER_ADMIN' ))
        ) {
            $deleteForm = $this->createDeleteForm( $udala );
            $editForm = $this->createForm( UdalaType::class, $udala );
            $editForm->handleRequest( $request );

            if ( $editForm->isSubmitted() && $editForm->isValid() ) {
                $this->em->persist( $udala );
                $this->em->flush();

                return $this->redirectToRoute( 'udala_edit', ['id' => $udala->getId()] );
            }

            return $this->render('udala/edit.html.twig', [
                'udala'       => $udala, 
                'edit_form'   => $editForm->createView(), 
                'delete_form' => $deleteForm->createView()
            ]);
        } else {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Udala entity.
     */
    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route(path: '/{id}', name: 'udala_delete', methods: ['DELETE'])]
    public function delete ( Request $request, Udala $udala ): RedirectResponse
    {
        $form = $this->createDeleteForm( $udala );
        $form->handleRequest( $request );
        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->em->remove( $udala );
            $this->em->flush();
        }

        return $this->redirectToRoute( 'udala_index' );
    }

    /**
     * Creates a form to delete a Udala entity.
     *
     * @param Udala $udala The Udala entity
     *
     * @return Form The form
     */
    private function createDeleteForm ( Udala $udala )
    {
        return $this->createFormBuilder()
            ->setAction( $this->generateUrl( 'udala_delete', ['id' => $udala->getId()] ) )
            ->setMethod( Request::METHOD_DELETE )
            ->getForm();
    }
}
