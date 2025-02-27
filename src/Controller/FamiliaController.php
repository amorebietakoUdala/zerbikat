<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Familia;
use App\Form\FamiliaType;
use App\Repository\FamiliaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Familia controller.
 */
#[Route(path: '/{_locale}/familia')]
class FamiliaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private FamiliaRepository $repo
    )
    {
    }


    /**
     * Lists all Familia entities.
     */
    #[IsGranted('ROLE_KUDEAKETA')]    
    #[Route(path: '/', defaults: ['page' => 1], name: 'familia_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'familia_index_paginated', methods: ['GET'])]
    public function index ( $page )
    {
        $familias = $this->repo->findBy(
            [],
            ['ordena' => 'ASC']
        );
        $deleteForms = [];
        foreach ( $familias as $familia ) {
            $deleteForms[ $familia->getId() ] = $this->createDeleteForm( $familia )->createView();
        }

        return $this->render('familia/index.html.twig',[
            'familias'    => $familias, 
            'deleteforms' => $deleteForms
        ]);
    }

    /**
     * Creates a new Familia entity.
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/new', name: 'familia_new', methods: ['GET', 'POST'])]
    public function new ( Request $request )
    {
        $familium = new Familia();
        $form = $this->createForm( FamiliaType::class, $familium );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $familium = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $familium->setUdala($user->getUdala());
            $this->em->persist( $familium );
            $this->em->flush();

            return $this->redirectToRoute( 'familia_index' );
        }

        return $this->render('familia/new.html.twig',[
            'familium' => $familium, 
            'form'     => $form->createView()
        ]);
    }

    /**
     * Deletes a Familia entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'familia_delete', methods: ['DELETE'], options: ['expose' => true])]
    public function delete ( Request $request, Familia $familium )
    {
        if ( $request->isXmlHttpRequest() ) {
            $this->em->remove( $familium );
            $this->em->flush();

            return New JsonResponse( ['result' => 'ok'] );
        }
        /** @var User $user */
        $user = $this->getUser();
        if ( (($this->isGranted( 'ROLE_ADMIN' )) && ($familium->getUdala() == $user->getUdala()))
            || ($this->isGranted( 'ROLE_SUPER_ADMIN' ))
        ) {
            $form = $this->createDeleteForm( $familium );
            $form->handleRequest( $request );
            if ( $form->isSubmitted() && $form->isValid() ) {
                $this->em->remove( $familium );
                $this->em->flush();
            }

            return $this->redirectToRoute( 'familia_index' );
        } else {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Displays a form to edit an existing Familia entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'familia_edit', methods: ['GET', 'POST'])]
    public function edit ( Request $request, Familia $familium )
    {
        /** @var User $user */
        $user = $this->getUser();
        if ( (($this->isGranted( 'ROLE_ADMIN' )) && ($familium->getUdala() == $user->getUdala()))
            || ($this->isGranted( 'ROLE_SUPER_ADMIN' ))
        ) {
            $deleteForm = $this->createDeleteForm( $familium );
            $editForm = $this->createForm( FamiliaType::class, $familium );
            $editForm->handleRequest( $request );

            if ( $editForm->isSubmitted() && $editForm->isValid() ) {
                $this->em->persist( $familium );
                $this->em->flush();

                return $this->redirectToRoute( 'familia_edit', ['id' => $familium->getId()] );
            }

            $azpifamiliak = $this->repo->findBy(
                ['parent' => $familium->getId()]
            );

            return $this->render(
                'familia/edit.html.twig',
                ['familium'     => $familium, 'edit_form'    => $editForm->createView(), 'delete_form'  => $deleteForm->createView(), 'azpifamiliak' => $azpifamiliak]
            );
        } else {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Finds and displays a Familia entity.
     */
    #[Route(path: '/{id}', name: 'familia_show', methods: ['GET'])]
    public function show ( Familia $familium ): Response
    {
        $deleteForm = $this->createDeleteForm( $familium );

        return $this->render(
            'familia/show.html.twig',
            ['familium'    => $familium, 'delete_form' => $deleteForm->createView()]
        );
    }

    /**
     * Creates a form to delete a Familia entity.
     *
     * @param Familia $familium The Familia entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm ( Familia $familium )
    {
        return $this->createFormBuilder()
            ->setAction( $this->generateUrl( 'familia_delete', ['id' => $familium->getId()] ) )
            ->setMethod( Request::METHOD_DELETE )
            ->getForm();
    }

}
