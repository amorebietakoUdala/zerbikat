<?php

    namespace App\Controller;

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Component\Routing\Annotation\Route;
    use App\Entity\Ordenantza;
    use App\Form\OrdenantzaType;
use App\Repository\OrdenantzaRepository;
use Doctrine\ORM\EntityManagerInterface;

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
         * @Route("/", name="ordenantza_index")
         * @Method("GET")
         */
        public function indexAction ()
        {

            if ( $this->isGranted( 'ROLE_ADMIN' ) ) {
                $ordenantzas = $this->repo->findAll();

                $deleteForms = array ();
                foreach ( $ordenantzas as $ordenantza ) {
                    $deleteForms[ $ordenantza->getId() ] = $this->createDeleteForm( $ordenantza )->createView();
                }

                return $this->render(
                    'ordenantza/index.html.twig',
                    array (
                        'ordenantzas' => $ordenantzas,
                        'deleteforms' => $deleteForms,
                    )
                );
            } else {
                return $this->redirectToRoute( 'backend_errorea' );
            }
        }

        /**
         * Creates a new Ordenantza entity.
         *
         * @Route("/new", name="ordenantza_new")
         * @Method({"GET", "POST"})
         */
        public function newAction ( Request $request )
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
                    array (
                        'ordenantza' => $ordenantza,
                        'form'       => $form->createView(),
                    )
                );
            } else {
                return $this->redirectToRoute( 'backend_errorea' );
            }
        }

        /**
         * Finds and displays a Ordenantza entity.
         *
         * @Route("/{id}", name="ordenantza_show")
         * @Method("GET")
         */
        public function showAction ( Ordenantza $ordenantza )
        {
            $deleteForm = $this->createDeleteForm( $ordenantza );

            return $this->render(
                'ordenantza/show.html.twig',
                array (
                    'ordenantza'  => $ordenantza,
                    'delete_form' => $deleteForm->createView(),
                )
            );
        }

        /**
         * Displays a form to edit an existing Ordenantza entity.
         *
         * @Route("/{id}/edit", name="ordenantza_edit")
         * @Method({"GET", "POST"})
         */
        public function editAction ( Request $request, Ordenantza $ordenantza )
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

                    return $this->redirectToRoute( 'ordenantza_edit', array ( 'id' => $ordenantza->getId() ) );
                }

                return $this->render(
                    'ordenantza/edit.html.twig',
                    array (
                        'ordenantza'  => $ordenantza,
                        'edit_form'   => $editForm->createView(),
                        'delete_form' => $deleteForm->createView(),
                    )
                );
            } else {
                return $this->redirectToRoute( 'backend_errorea' );
            }
        }

        /**
         * Deletes a Ordenantza entity.
         *
         * @Route("/{id}", name="ordenantza_delete")
         * @Method("DELETE")
         */
        public function deleteAction ( Request $request, Ordenantza $ordenantza )
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
         * @return \Symfony\Component\Form\Form The form
         */
        private function createDeleteForm ( Ordenantza $ordenantza )
        {
            return $this->createFormBuilder()
                ->setAction( $this->generateUrl( 'ordenantza_delete', array ( 'id' => $ordenantza->getId() ) ) )
                ->setMethod( 'DELETE' )
                ->getForm();
        }
    }
