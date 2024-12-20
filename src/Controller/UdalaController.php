<?php

namespace App\Controller;

use Pagerfanta\Exception\NotValidCurrentPageException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Udala;
use App\Form\UdalaType;
use App\Repository\UdalaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * Udala controller.
 *
 * @Route("/{_locale}/udala")
 */
class UdalaController extends AbstractController
{
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, UdalaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Udala entities.
     *
     * @Route("/", defaults={"page" = 1}, name="udala_index")
     * @Route("/page{page}", name="udala_index_paginated")
     * @Method("GET")
     */
    public function indexAction ( $page )
    {

        if ( $this->isGranted( 'ROLE_SUPER_ADMIN' ) ) {
            $udalas = $this->repo->findAll();

            $adapter = new ArrayAdapter( $udalas );
            $pagerfanta = new Pagerfanta( $adapter );

            $deleteForms = array ();
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
            } catch ( NotValidCurrentPageException $e ) {
                throw $this->createNotFoundException( "Orria ez da existitzen" );
            }

            return $this->render(
                'udala/index.html.twig',
                array (
                    'udalas'      => $entities,
                    'deleteforms' => $deleteForms,
                    'pager'       => $pagerfanta,
                )
            );
        } else {
            if ( $this->isGranted( 'ROLE_ADMIN' ) ) {
                // Begiratu ea erabiltzaileak Udala baduen
                if ($this->getUser()->getUdala()) {
                    return $this->redirectToRoute(
                        'udala_show',
                        array ('id' => $this->getUser()->getUdala()->getId())
                    );
                } else {
                    return $this->redirectToRoute(
                        'udala_ez'
                    );
                }

            } else {
                return $this->redirectToRoute( 'backend_errorea' );
            }
        }
    }

    /**
     * Udalik ez errorea.
     *
     * @Route("/udala/errorea", defaults={"page" = 1}, name="udala_ez")
     * @Method("GET")
     */
    public function udalaezAction ( $page )
    {
        return $this->render(
            'udala/index.html.twig'
        );
    }

    /**
     * Creates a new Udala entity.
     *
     * @Route("/new", name="udala_new")
     * @Method({"GET", "POST"})
     */
    public function newAction ( Request $request )
    {

        if ( $this->isGranted( 'ROLE_SUPER_ADMIN' ) ) {
            $udala = new Udala();
            $form = $this->createForm( UdalaType::class, $udala );
            $form->handleRequest( $request );

            if ( $form->isSubmitted() && $form->isValid() ) {
                $this->em->persist( $udala );
                $this->em->flush();

                return $this->redirectToRoute( 'udala_show', array ('id' => $udala->getId()) );
            }

            return $this->render(
                'udala/new.html.twig',
                array (
                    'udala' => $udala,
                    'form'  => $form->createView(),
                )
            );
        } else {
            return $this->redirectToRoute( 'backend_errorea' );
        }
    }

    /**
     * Finds and displays a Udala entity.
     *
     * @Route("/{id}", name="udala_show")
     * @Method("GET")
     */
    public function showAction ( Udala $udala )
    {
        $deleteForm = $this->createDeleteForm( $udala );

        return $this->render(
            'udala/show.html.twig',
            array (
                'udala'       => $udala,
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Displays a form to edit an existing Udala entity.
     *
     * @Route("/{id}/edit", name="udala_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction ( Request $request, Udala $udala )
    {

        if ( (($this->isGranted( 'ROLE_ADMIN' )) && ($udala == $this->getUser()->getUdala()))
            || ($this->isGranted( 'ROLE_SUPER_ADMIN' ))
        ) {
            $deleteForm = $this->createDeleteForm( $udala );
            $editForm = $this->createForm( UdalaType::class, $udala );
            $editForm->handleRequest( $request );

            if ( $editForm->isSubmitted() && $editForm->isValid() ) {
                $this->em->persist( $udala );
                $this->em->flush();

                return $this->redirectToRoute( 'udala_edit', array ('id' => $udala->getId()) );
            }

            return $this->render(
                'udala/edit.html.twig',
                array (
                    'udala'       => $udala,
                    'edit_form'   => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                )
            );
        } else {
            return $this->redirectToRoute( 'backend_errorea' );
        }
    }

    /**
     * Deletes a Udala entity.
     *
     * @Route("/{id}", name="udala_delete")
     * @Method("DELETE")
     */
    public function deleteAction ( Request $request, Udala $udala )
    {

        if ( $this->isGranted( 'ROLE_SUPER_ADMIN' ) ) {
            $form = $this->createDeleteForm( $udala );
            $form->handleRequest( $request );
            if ( $form->isSubmitted() && $form->isValid() ) {
                $this->em->remove( $udala );
                $this->em->flush();
            }

            return $this->redirectToRoute( 'udala_index' );
        } else {
            //baimenik ez
            return $this->redirectToRoute( 'backend_errorea' );
        }
    }

    /**
     * Creates a form to delete a Udala entity.
     *
     * @param Udala $udala The Udala entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm ( Udala $udala )
    {
        return $this->createFormBuilder()
            ->setAction( $this->generateUrl( 'udala_delete', array ('id' => $udala->getId()) ) )
            ->setMethod( 'DELETE' )
            ->getForm();
    }
}