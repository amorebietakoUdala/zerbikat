<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Kalea;
use App\Form\KaleaType;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * Kalea controller.
 *
 * @Route("/{_locale}/kalea")
 */
class KaleaController extends AbstractController
{
    /**
     * Lists all Kalea entities.
     *
     * @Route("/", name="kalea_index")
     * @Method("GET")
     */
    public function indexAction()
    {

        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $em = $this->getDoctrine()->getManager();
            $kaleas = $em->getRepository('App:Kalea')->findAll();

            $deleteForms = array();
            foreach ($kaleas as $kalea) {
                $deleteForms[$kalea->getId()] = $this->createDeleteForm($kalea)->createView();
            }


            return $this->render('kalea/index.html.twig', array(
                'kaleas' => $kaleas,
                'deleteforms' => $deleteForms
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Kalea entity.
     *
     * @Route("/new", name="kalea_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN')) 
        {
            $kalea = new Kalea();
            $form = $this->createForm('App\Form\KaleaType', $kalea);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());
            
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($kalea);
                $em->flush();
    
//                return $this->redirectToRoute('kalea_show', array('id' => $kalea->getId()));
                return $this->redirectToRoute('kalea_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }
    
            return $this->render('kalea/new.html.twig', array(
                'kalea' => $kalea,
                'form' => $form->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Finds and displays a Kalea entity.
     *
     * @Route("/{id}", name="kalea_show")
     * @Method("GET")
     */
    public function showAction(Kalea $kalea)
    {
        $deleteForm = $this->createDeleteForm($kalea);

        return $this->render('kalea/show.html.twig', array(
            'kalea' => $kalea,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Kalea entity.
     *
     * @Route("/{id}/edit", name="kalea_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Kalea $kalea)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($kalea->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($kalea);
            $editForm = $this->createForm('App\Form\KaleaType', $kalea);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($kalea);
                $em->flush();
    
                return $this->redirectToRoute('kalea_edit', array('id' => $kalea->getId()));
            }
    
            return $this->render('kalea/edit.html.twig', array(
                'kalea' => $kalea,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Deletes a Kalea entity.
     *
     * @Route("/{id}", name="kalea_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Kalea $kalea)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($kalea->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($kalea);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($kalea);
                $em->flush();
            }
            return $this->redirectToRoute('kalea_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Creates a form to delete a Kalea entity.
     *
     * @param Kalea $kalea The Kalea entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Kalea $kalea)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('kalea_delete', array('id' => $kalea->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
