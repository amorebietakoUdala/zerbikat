<?php

    namespace App\Controller;

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
        use Symfony\Component\Routing\Annotation\Route;
    use App\Entity\Ordenantza;
    use App\Form\OrdenantzaType;
use App\Repository\OrdenantzaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

    /**
     * Ordenantza controller.
     *
     * @Route("/{_locale}/ordenantza")
     */
    class OrdenantzaController extends AbstractController
    {
        private $repo;
        private $em;
    
        public function __construct(EntityManagerInterface $em, OrdenantzaRepository $repo)
        {
            $this->repo = $repo;
            $this->em = $em;
        }
    
        /**
         * Lists all Ordenantza entities.
         *
         * @Route("/", name="ordenantza_index", methods={"GET"})
         */
        public function index ()
        {

            if ( $this->isGranted( 'ROLE_ADMIN' ) ) {
                $ordenantzas = $this->repo->findAll();

                $deleteForms = [];
                foreach ( $ordenantzas as $ordenantza ) {
                    $deleteForms[ $ordenantza->getId() ] = $this->createDeleteForm( $ordenantza )->createView();
                }

                return $this->render(
                    'ordenantza/index.html.twig',
                    ['ordenantzas' => $ordenantzas, 'deleteforms' => $deleteForms]
                );
            } else {
                return $this->redirectToRoute( 'backend_errorea' );
            }
        }

        /**
         * Creates a new Ordenantza entity.
         *
         * @Route("/new", name="ordenantza_new", methods={"GET", "POST"})
         */
        public function new ( Request $request )
        {

            if ( $this->isGranted( 'ROLE_ADMIN' ) ) {
                $ordenantza = new Ordenantza();
                $form = $this->createForm( OrdenantzaType::class, $ordenantza );
                $form->handleRequest( $request );

                if ( $form->isSubmitted() && $form->isValid() ) {
                    $ordenantza->setCreatedAt( new \DateTime() );
                    $ordenantza->setUpdatedAt( new \DateTime() );
                    $this->em->persist( $ordenantza );
                    $this->em->flush();

                    return $this->redirectToRoute( 'ordenantza_index' );
                } else {
                    $form->getData()->setUdala( $this->getUser()->getUdala() );
                    $form->setData( $form->getData() );
                }

                return $this->render(
                    'ordenantza/new.html.twig',
                    ['ordenantza' => $ordenantza, 'form'       => $form->createView()]
                );
            } else {
                return $this->redirectToRoute( 'backend_errorea' );
            }
        }

        /**
         * Finds and displays a Ordenantza entity.
         *
         * @Route("/{id}", name="ordenantza_show", methods={"GET"})
         */
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
         *
         * @Route("/{id}/edit", name="ordenantza_edit", methods={"GET", "POST"})
         */
        public function edit ( Request $request, Ordenantza $ordenantza )
        {

            if ( (($this->isGranted( 'ROLE_ADMIN' )) && ($ordenantza->getUdala() == $this->getUser()->getUdala(
                        )))
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
                return $this->redirectToRoute( 'backend_errorea' );
            }
        }

        /**
         * Deletes a Ordenantza entity.
         *
         * @Route("/{id}", name="ordenantza_delete", methods={"DELETE"})
         */
        public function delete ( Request $request, Ordenantza $ordenantza ): RedirectResponse
        {

            if ( (($this->isGranted( 'ROLE_ADMIN' )) && ($ordenantza->getUdala() == $this->getUser()->getUdala(
                        )))
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
                return $this->redirectToRoute( 'backend_errorea' );
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
