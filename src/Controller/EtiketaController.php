<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Etiketa;
use App\Form\EtiketaType;
use App\Repository\EtiketaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * Etiketa controller.
 *
 * @Route("/{_locale}/etiketa")
 */
class EtiketaController extends AbstractController
{
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, EtiketaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Etiketa entities.
     *
     * @Route("/", defaults={"page" = 1}, name="etiketa_index")
     * @Route("/page{page}", name="etiketa_index_paginated")
     * @Method("GET")
     */
    public function indexAction($page)
    {

        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $etiketas = $this->repo->findBy( array(), array('etiketaeu'=>'ASC') );

            $deleteForms = array();
            foreach ($etiketas as $etiketa) {
                $deleteForms[$etiketa->getId()] = $this->createDeleteForm($etiketa)->createView();
            }

            return $this->render('etiketa/index.html.twig', array(
                'etiketas' => $etiketas,
                'deleteforms' => $deleteForms,
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Etiketa entity.
     *
     * @Route("/new", name="etiketa_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN'))
        {
            $etiketum = new Etiketa();
            $form = $this->createForm(EtiketaType::class, $etiketum);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($etiketum);
                $this->em->flush();

//                return $this->redirectToRoute('etiketa_show', array('id' => $etiketum->getId()));
                return $this->redirectToRoute('etiketa_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('etiketa/new.html.twig', array(
                'etiketum' => $etiketum,
                'form' => $form->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Etiketa entity.
     *
     * @Route("/{id}", name="etiketa_show")
     * @Method("GET")
     */
    public function showAction(Etiketa $etiketum)
    {
        $deleteForm = $this->createDeleteForm($etiketum);

        return $this->render('etiketa/show.html.twig', array(
            'etiketum' => $etiketum,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Etiketa entity.
     *
     * @Route("/{id}/edit", name="etiketa_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Etiketa $etiketum)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($etiketum->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($etiketum);
            $editForm = $this->createForm(EtiketaType::class, $etiketum);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($etiketum);
                $this->em->flush();
    
                return $this->redirectToRoute('etiketa_edit', array('id' => $etiketum->getId()));
            }
    
            return $this->render('etiketa/edit.html.twig', array(
                'etiketum' => $etiketum,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Deletes a Etiketa entity.
     *
     * @Route("/{id}", name="etiketa_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Etiketa $etiketum)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($etiketum->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($etiketum);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($etiketum);
                $this->em->flush();
            }
            return $this->redirectToRoute('etiketa_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a form to delete a Etiketa entity.
     *
     * @param Etiketa $etiketum The Etiketa entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Etiketa $etiketum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('etiketa_delete', array('id' => $etiketum->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
