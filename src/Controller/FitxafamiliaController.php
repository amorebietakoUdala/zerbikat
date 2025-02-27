<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Fitxafamilia;
use App\Form\FitxafamiliaType;
use App\Repository\FitxafamiliaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Fitxafamilia controller.
 */
#[Route(path: '/{_locale}/fitxafamilia')]
class FitxafamiliaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private  FitxafamiliaRepository $repo
    )
    {
    }


    /**
     * Fitxa-Familiak ordena duen ikusi.
     */
    #[Route(path: '/api/fitxafamiliakordenadauka/{id}/{fitxa_id}/{familia_id}', name: 'api_fitxafamiliahasorden', options: ['expose' => true], methods: ['GET'])]
    public function fitxafamiliahasorden ( $id, $fitxa_id, $familia_id ): \Symfony\Component\HttpFoundation\JsonResponse
    {
        /** @var Fitxafamilia $fitxafamilia */
        $fitxafamilia = $this->repo->find( $id );
        if ( $fitxafamilia ) {
            $resp = ['ordena' => $fitxafamilia->getOrdena()];
        } else {
            $query = $this->em->createQuery(
                '
            SELECT MAX(f.ordena) as ordena
            FROM App:Fitxafamilia f              
            WHERE f.familia = :familia_id  AND f.fitxa = :fitxa
            '
            );
            $query->setParameter( 'familia_id', $familia_id );
            $query->setParameter( 'fitxa', $fitxafamilia->getFitxa()->getId() );

            $resp = $query->getSingleResult();
        }

        return New JsonResponse( $resp );

    }

    /**
     * Fitxa-Familiak datooren ordena eman.
     */
    #[Route(path: '/api/fitxafamilianextorden/{fitxa_id}/{familia_id}', name: 'api_fitxafamilianextorden', options: ['expose' => true], methods: ['GET'])]
    public function fitxafamilianextorden ( $fitxa_id, $familia_id ): \Symfony\Component\HttpFoundation\JsonResponse
    {
        // 1-. Badagoen begiratu

        $fitxafamilia = $this->repo->findOneBy(
            ['fitxa' => $fitxa_id, 'familia' => $familia_id]
        );

        if ($fitxafamilia) {
            return new JsonResponse(['ordena'=>-1]);
        }

        $query = $this->em->createQuery(
            '
        SELECT MAX(f.ordena) as ordena
        FROM App:Fitxafamilia f              
        WHERE f.familia = :familia_id  
        '
        );
//            $query->setParameter( 'fitxa_id', $fitxa_id );
        $query->setParameter( 'familia_id', $familia_id );

        $resp = $query->getSingleResult();

        return New JsonResponse( $resp );

    }


    /**
     * Lists all Fitxafamilia entities.
     */
    #[Route(path: '/', name: 'fitxafamilia_index', methods: ['GET'])]
    public function index (): Response
    {
        $fitxafamilias = $this->repo->findAll();

        return $this->render(
            'fitxafamilia/index.html.twig',
            ['fitxafamilias' => $fitxafamilias]
        );
    }

    /**
     * Creates a new Fitxafamilia entity.
     */
    #[Route(path: '/newfromfitxa', name: 'fitxafamilia_newfromfitxa', methods: ['GET', 'POST'])]
    public function newfromfitxa ( Request $request )
    {
        /** @var User $user */
        $user = $this->getUser();
        $fitxafamilium = new Fitxafamilia();
        $fitxafamilium->setUdala( $user->getUdala() );
        $form = $this->createForm( FitxafamiliaType::class, $fitxafamilium );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->em->persist( $fitxafamilium );
            $this->em->flush();

            return $this->redirect(
                $this->generateUrl(
                    'fitxa_edit',
                    ['id' => $fitxafamilium->getFitxa()->getId()]
                ).'#gehituFamilia'
            );
        }

        return $this->render(
            'fitxafamilia/new.html.twig',
            ['fitxafamilium' => $fitxafamilium, 'form'          => $form->createView()]
        );
    }

    /**
     * Creates a new Fitxafamilia entity.
     */
    #[Route(path: '/new', name: 'fitxafamilia_new', methods: ['GET', 'POST'])]
    public function new ( Request $request )
    {
        /** @var User $user */
        $user = $this->getUser();
        $fitxafamilium = new Fitxafamilia();
        $fitxafamilium->setUdala( $user->getUdala() );
        $form = $this->createForm( FitxafamiliaType::class, $fitxafamilium );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->em->persist( $fitxafamilium );
            $this->em->flush();

            return $this->redirectToRoute( 'fitxafamilia_show', ['id' => $fitxafamilium->getId()] );
        }

        return $this->render(
            'fitxafamilia/new.html.twig',
            ['fitxafamilium' => $fitxafamilium, 'form'          => $form->createView()]
        );
    }

    /**
     * Finds and displays a Fitxafamilia entity.
     */
    #[Route(path: '/{id}', name: 'fitxafamilia_show', methods: ['GET'])]
    public function show ( Fitxafamilia $fitxafamilium ): Response
    {
        $deleteForm = $this->createDeleteForm( $fitxafamilium );

        return $this->render(
            'fitxafamilia/show.html.twig',
            ['fitxafamilium' => $fitxafamilium, 'delete_form'   => $deleteForm->createView()]
        );
    }

    /**
     * Displays a form to edit an existing Fitxafamilia entity.
     */
    #[Route(path: '/{id}/edit', name: 'fitxafamilia_edit', options: ['expose' => true], methods: ['GET', 'POST'])]
    public function edit ( Request $request, Fitxafamilia $fitxafamilium )
    {
        $deleteForm = $this->createDeleteForm( $fitxafamilium );
        $editForm = $this->createForm(
            \App\Form\FitxafamiliaType::class,
            $fitxafamilium,
            [
                'action' => $this->generateUrl(
                    'fitxafamilia_edit',
                    ['id' => $fitxafamilium->getFitxa()->getId()]
                ),
                'method' => "POST",
            ]
        );

        $editForm->handleRequest( $request );

        if ( $editForm->isSubmitted() && $editForm->isValid() ) {
            $this->em->persist( $fitxafamilium );
            $this->em->flush();

            return $this->redirect(
                $this->generateUrl(
                    'fitxa_edit',
                    ['id' => $fitxafamilium->getFitxa()->getId()]
                ).'#gehituFamilia'
            );
        }

        return $this->render(
            'fitxafamilia/edit.html.twig',
            ['fitxafamilium' => $fitxafamilium, 'edit_form'     => $editForm->createView(), 'delete_form'   => $deleteForm->createView()]
        );
    }

    /**
     * Deletes a Fitxafamilia entity.
     */
    #[Route(path: '/{id}', name: 'fitxafamilia_delete', options: ['expose' => true], methods: ['DELETE'])]
    public function delete ( Request $request, Fitxafamilia $fitxafamilium )
    {
        if($request->isXmlHttpRequest()) {
            $this->em->remove( $fitxafamilium );
            $this->em->flush();
            return New JsonResponse(['result' => 'ok']);
        }
        $form = $this->createDeleteForm( $fitxafamilium );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->em->remove( $fitxafamilium );
            $this->em->flush();
        }

        return $this->redirectToRoute( 'fitxafamilia_index' );
    }

    /**
     * Creates a form to delete a Fitxafamilia entity.
     *
     * @param Fitxafamilia $fitxafamilium The Fitxafamilia entity
     *
     * @return Form The form
     */
    private function createDeleteForm ( Fitxafamilia $fitxafamilium )
    {
        return $this->createFormBuilder()
            ->setAction( $this->generateUrl( 'fitxafamilia_delete', ['id' => $fitxafamilium->getId()] ) )
            ->setMethod( Request::METHOD_DELETE )
            ->getForm();
    }
}
