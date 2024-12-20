<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Familia;
use App\Form\FamiliaType;
use App\Repository\FamiliaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * Familia controller.
 *
 * @Route("/{_locale}/familia")
 */
class FamiliaController extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, FamiliaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }


    /**
     * Lists all Familia entities.
     *
     * @Route("/", defaults={"page" = 1}, name="familia_index")
     * @Route("/page{page}", name="familia_index_paginated")
     * @Method("GET")
     */
    public function index ( $page )
    {

        if ( $this->isGranted( 'ROLE_KUDEAKETA' ) ) {
            $familias = $this->repo->findBy(
                [],
                ['ordena' => 'ASC']
            );
            $deleteForms = [];
            foreach ( $familias as $familia ) {
                $deleteForms[ $familia->getId() ] = $this->createDeleteForm( $familia )->createView();
            }

            return $this->render(
                'familia/index.html.twig',
                ['familias'    => $familias, 'deleteforms' => $deleteForms]
            );
        } else {
            return $this->redirectToRoute( 'backend_errorea' );
        }
    }

    /**
     * Creates a new Familia entity.
     *
     * @Route("/new", name="familia_new")
     * @Method({"GET", "POST"})
     */
    public function new ( Request $request )
    {

        if ( $this->isGranted( 'ROLE_ADMIN' ) ) {
            $familium = new Familia();
            $form = $this->createForm( FamiliaType::class, $familium );
            $form->handleRequest( $request );

            if ( $form->isSubmitted() && $form->isValid() ) {
                $this->em->persist( $familium );
                $this->em->flush();

                return $this->redirectToRoute( 'familia_index' );
            } else {
                $form->getData()->setUdala( $this->getUser()->getUdala() );
                $form->setData( $form->getData() );
            }

            return $this->render(
                'familia/new.html.twig',
                ['familium' => $familium, 'form'     => $form->createView()]
            );
        } else {
            return $this->redirectToRoute( 'backend_errorea' );
        }
    }

    /**
     * Deletes a Familia entity.
     *
     * @Route("/{id}", name="familia_delete", methods={"DELETE"}, options={"expose"=true})
     */
    public function delete ( Request $request, Familia $familium )
    {
        if ( $request->isXmlHttpRequest() ) {
            $this->em->remove( $familium );
            $this->em->flush();

            return New JsonResponse( ['result' => 'ok'] );
        }


        if ( (($this->isGranted( 'ROLE_ADMIN' )) && ($familium->getUdala() == $this->getUser()->getUdala()))
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
            return $this->redirectToRoute( 'backend_errorea' );
        }
    }

    /**
     * Displays a form to edit an existing Familia entity.
     *
     * @Route("/{id}/edit", name="familia_edit")
     * @Method({"GET", "POST"})
     */
    public function edit ( Request $request, Familia $familium )
    {

        if ( (($this->isGranted( 'ROLE_ADMIN' )) && ($familium->getUdala() == $this->getUser()->getUdala()))
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
            return $this->redirectToRoute( 'backend_errorea' );
        }
    }

    /**
     * Finds and displays a Familia entity.
     *
     * @Route("/{id}", name="familia_show")
     * @Method("GET")
     */
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
