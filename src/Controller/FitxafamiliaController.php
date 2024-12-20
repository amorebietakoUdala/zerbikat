<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Fitxafamilia;
use App\Form\FitxafamiliaType;
use App\Repository\FitxafamiliaRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Fitxafamilia controller.
 *
 * @Route("/{_locale}/fitxafamilia")
 */
class FitxafamiliaController extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, FitxafamiliaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }


    /**
     * Fitxa-Familiak ordena duen ikusi.
     *
     * @Route("/api/fitxafamiliakordenadauka/{id}/{fitxa_id}/{familia_id}", name="api_fitxafamiliahasorden", options={"expose"=true})
     * @Method("GET")
     */
    public function fitxafamiliahasordenAction ( $id, $fitxa_id, $familia_id )
    {
        /** @var Fitxafamilia $fitxafamilia */
        $fitxafamilia = $this->repo->find( $id );
        if ( $fitxafamilia ) {
            $resp = array ('ordena' => $fitxafamilia->getOrdena());
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
     *
     * @Route("/api/fitxafamilianextorden/{fitxa_id}/{familia_id}", name="api_fitxafamilianextorden", options={"expose"=true})
     * @Method("GET")
     */
    public function fitxafamilianextordenAction ( $fitxa_id, $familia_id )
    {
        // 1-. Badagoen begiratu

        $fitxafamilia = $this->repo->findOneBy(
            array ('fitxa' => $fitxa_id, 'familia' => $familia_id)
        );

        if ($fitxafamilia) {
            return new JsonResponse(array('ordena'=>-1));
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
     *
     * @Route("/", name="fitxafamilia_index")
     * @Method("GET")
     */
    public function indexAction ()
    {
        $fitxafamilias = $this->repo->findAll();

        return $this->render(
            'fitxafamilia/index.html.twig',
            array (
                'fitxafamilias' => $fitxafamilias,
            )
        );
    }

    /**
     * Creates a new Fitxafamilia entity.
     *
     * @Route("/newfromfitxa", name="fitxafamilia_newfromfitxa")
     * @Method({"GET", "POST"})
     */
    public function newfromfitxaAction ( Request $request )
    {
        $fitxafamilium = new Fitxafamilia();
        $fitxafamilium->setUdala( $this->getUser()->getUdala() );
        $form = $this->createForm( FitxafamiliaType::class, $fitxafamilium );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->em->persist( $fitxafamilium );
            $this->em->flush();

            return $this->redirect(
                $this->generateUrl(
                    'fitxa_edit',
                    array ('id' => $fitxafamilium->getFitxa()->getId())
                ).'#gehituFamilia'
            );
        }

        return $this->render(
            'fitxafamilia/new.html.twig',
            array (
                'fitxafamilium' => $fitxafamilium,
                'form'          => $form->createView(),
            )
        );
    }

    /**
     * Creates a new Fitxafamilia entity.
     *
     * @Route("/new", name="fitxafamilia_new")
     * @Method({"GET", "POST"})
     */
    public function newAction ( Request $request )
    {
        $fitxafamilium = new Fitxafamilia();
        $fitxafamilium->setUdala( $this->getUser()->getUdala() );
        $form = $this->createForm( FitxafamiliaType::class, $fitxafamilium );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->em->persist( $fitxafamilium );
            $this->em->flush();

            return $this->redirectToRoute( 'fitxafamilia_show', array ('id' => $fitxafamilium->getId()) );
        }

        return $this->render(
            'fitxafamilia/new.html.twig',
            array (
                'fitxafamilium' => $fitxafamilium,
                'form'          => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Fitxafamilia entity.
     *
     * @Route("/{id}", name="fitxafamilia_show")
     * @Method("GET")
     */
    public function showAction ( Fitxafamilia $fitxafamilium )
    {
        $deleteForm = $this->createDeleteForm( $fitxafamilium );

        return $this->render(
            'fitxafamilia/show.html.twig',
            array (
                'fitxafamilium' => $fitxafamilium,
                'delete_form'   => $deleteForm->createView(),
            )
        );
    }

    /**
     * Displays a form to edit an existing Fitxafamilia entity.
     *
     * @Route("/{id}/edit", name="fitxafamilia_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction ( Request $request, Fitxafamilia $fitxafamilium )
    {
        $deleteForm = $this->createDeleteForm( $fitxafamilium );
        $editForm = $this->createForm(
            'App\Form\FitxafamiliaType',
            $fitxafamilium,
            [
                'action' => $this->generateUrl(
                    'fitxafamilia_edit',
                    array ('id' => $fitxafamilium->getFitxa()->getId())
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
                    array ('id' => $fitxafamilium->getFitxa()->getId())
                ).'#gehituFamilia'
            );
        }

        return $this->render(
            'fitxafamilia/edit.html.twig',
            array (
                'fitxafamilium' => $fitxafamilium,
                'edit_form'     => $editForm->createView(),
                'delete_form'   => $deleteForm->createView(),
            )
        );
    }

    /**
     * Deletes a Fitxafamilia entity.
     *
     * @Route("/{id}", name="fitxafamilia_delete", options={"expose"=true})
     * @Method("DELETE")
     */
    public function deleteAction ( Request $request, Fitxafamilia $fitxafamilium )
    {
        if($request->isXmlHttpRequest()) {
            $this->em->remove( $fitxafamilium );
            $this->em->flush();
            return New JsonResponse(array('result' => 'ok'));
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
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm ( Fitxafamilia $fitxafamilium )
    {
        return $this->createFormBuilder()
            ->setAction( $this->generateUrl( 'fitxafamilia_delete', array ('id' => $fitxafamilium->getId()) ) )
            ->setMethod( 'DELETE' )
            ->getForm();
    }
}
