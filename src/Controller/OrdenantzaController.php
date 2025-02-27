<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Ordenantza;
use App\Form\OrdenantzaType;
use App\Repository\OrdenantzaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

    /**
     * Ordenantza controller.
     */
    #[Route(path: '/{_locale}/ordenantza')]
    class OrdenantzaController extends AbstractController
    {
    
        public function __construct(
            private EntityManagerInterface $em, 
            private OrdenantzaRepository $repo
        )
        {
        }
    
        /**
         * Lists all Ordenantza entities.
         */
        #[IsGranted('ROLE_ADMIN')]
        #[Route(path: '/', name: 'ordenantza_index', methods: ['GET'])]
        public function index ()
        {
            $ordenantzas = $this->repo->findAll();

            $deleteForms = [];
            foreach ( $ordenantzas as $ordenantza ) {
                $deleteForms[ $ordenantza->getId() ] = $this->createDeleteForm( $ordenantza )->createView();
            }

            return $this->render(
                'ordenantza/index.html.twig',
                ['ordenantzas' => $ordenantzas, 'deleteforms' => $deleteForms]
            );
        }

        /**
         * Creates a new Ordenantza entity.
         */
        #[IsGranted('ROLE_ADMIN')]
        #[Route(path: '/new', name: 'ordenantza_new', methods: ['GET', 'POST'])]
        public function new ( Request $request )
        {
            $ordenantza = new Ordenantza();
            $form = $this->createForm( OrdenantzaType::class, $ordenantza );
            $form->handleRequest( $request );

            if ( $form->isSubmitted() && $form->isValid() ) {
                $ordenantza = $form->getData();
                /** @var User $user */
                $user = $this->getUser();
                $ordenantza->setCreatedAt( new \DateTime() );
                $ordenantza->setUpdatedAt( new \DateTime() );
                $ordenantza->setUdala($user->getUdala());
                $this->em->persist( $ordenantza );
                $this->em->flush();

                return $this->redirectToRoute( 'ordenantza_index' );
            }

            return $this->render(
                'ordenantza/new.html.twig',
                ['ordenantza' => $ordenantza, 'form'       => $form->createView()]
            );
        }

        /**
         * Finds and displays a Ordenantza entity.
         */
        #[Route(path: '/{id}', name: 'ordenantza_show', methods: ['GET'])]
        public function show ( Ordenantza $ordenantza ): Response
        {
            $deleteForm = $this->createDeleteForm( $ordenantza );

            return $this->render(
                'ordenantza/show.html.twig',
                ['ordenantza'  => $ordenantza, 'delete_form' => $deleteForm->createView()]
            );
        }

        /**
         * Displays a form to edit an existing Ordenantza entity.
         */
        #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
        #[Route(path: '/{id}/edit', name: 'ordenantza_edit', methods: ['GET', 'POST'])]
        public function edit ( Request $request, Ordenantza $ordenantza )
        {
            /** @var User $user */
            $user = $this->getUser();
            if ( (($this->isGranted( 'ROLE_ADMIN' )) && ($ordenantza->getUdala() == $user->getUdala()))
                || ($this->isGranted( 'ROLE_SUPER_ADMIN' ))
            ) {
                $deleteForm = $this->createDeleteForm( $ordenantza );
                $editForm = $this->createForm( OrdenantzaType::class, $ordenantza );
                $editForm->handleRequest( $request );

                if ( $editForm->isSubmitted() && $editForm->isValid() ) {
                    $this->em->persist( $ordenantza );
                    $this->em->flush();

                    return $this->redirectToRoute( 'ordenantza_edit', ['id' => $ordenantza->getId()] );
                }

                return $this->render(
                    'ordenantza/edit.html.twig',
                    ['ordenantza'  => $ordenantza, 'edit_form'   => $editForm->createView(), 'delete_form' => $deleteForm->createView()]
                );
            } else {
                throw new AccessDeniedHttpException('Access Denied');
            }
        }

        /**
         * Deletes a Ordenantza entity.
         */
        #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
        #[Route(path: '/{id}', name: 'ordenantza_delete', methods: ['DELETE'])]
        public function delete ( Request $request, Ordenantza $ordenantza ): RedirectResponse
        {
            /** @var User $user */
            $user = $this->getUser();
            if ( (($this->isGranted( 'ROLE_ADMIN' )) && ($ordenantza->getUdala() == $user->getUdala()))
                || ($this->isGranted( 'ROLE_SUPER_ADMIN' ))
            ) {
                $form = $this->createDeleteForm( $ordenantza );
                $form->handleRequest( $request );
                if ( $form->isSubmitted() && $form->isValid() ) {
                    $this->em->remove( $ordenantza );
                    $this->em->flush();
                }

                return $this->redirectToRoute( 'ordenantza_index' );
            } else {
                //baimenik ez
                throw new AccessDeniedHttpException('Access Denied');
            }
        }

        /**
         * Creates a form to delete a Ordenantza entity.
         *
         * @param Ordenantza $ordenantza The Ordenantza entity
         *
         * @return Form The form
         */
        private function createDeleteForm ( Ordenantza $ordenantza )
        {
            return $this->createFormBuilder()
                ->setAction( $this->generateUrl( 'ordenantza_delete', ['id' => $ordenantza->getId()] ) )
                ->setMethod( Request::METHOD_DELETE )
                ->getForm();
        }
    }
