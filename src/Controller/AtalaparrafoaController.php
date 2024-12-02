<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Atalaparrafoa;
use App\Form\AtalaparrafoaType;

/**
 * Atalaparrafoa controller.
 *
 * @Route("/{_locale}/atalaparrafoa")
 */
class AtalaparrafoaController extends AbstractController
{
    /**
     * Lists all Atalaparrafoa entities.
     *
     * @Route("/", name="atalaparrafoa_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $atalaparrafoas = $em->getRepository('App:Atalaparrafoa')->findAll();

            $deleteForms = array();
            foreach ($atalaparrafoas as $parrafoa) {
                $deleteForms[$parrafoa->getId()] = $this->createDeleteForm($parrafoa)->createView();
            }

            return $this->render('atalaparrafoa/index.html.twig', array(
                'atalaparrafoas' => $atalaparrafoas,
                'deleteforms' => $deleteForms
            ));
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Atalaparrafoa entity.
     *
     * @Route("/new", name="atalaparrafoa_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $atalaparrafoa = new Atalaparrafoa();
            $form = $this->createForm('App\Form\AtalaparrafoaType', $atalaparrafoa);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($atalaparrafoa);
                $em->flush();

                return $this->redirectToRoute('atalaparrafoa_show', array('id' => $atalaparrafoa->getId()));
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('atalaparrafoa/new.html.twig', array(
                'atalaparrafoa' => $atalaparrafoa,
                'form' => $form->createView(),
            ));
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Atalaparrafoa entity.
     *
     * @Route("/{id}", name="atalaparrafoa_show")
     * @Method("GET")
     */
    public function showAction(Atalaparrafoa $atalaparrafoa)
    {
        $deleteForm = $this->createDeleteForm($atalaparrafoa);

        return $this->render('atalaparrafoa/show.html.twig', array(
            'atalaparrafoa' => $atalaparrafoa,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Atalaparrafoa entity.
     *
     * @Route("/{id}/edit", name="atalaparrafoa_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Atalaparrafoa $atalaparrafoa)
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($atalaparrafoa->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($atalaparrafoa);
            $editForm = $this->createForm('App\Form\AtalaparrafoaType', $atalaparrafoa);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($atalaparrafoa);
                $em->flush();

                return $this->redirectToRoute('atalaparrafoa_edit', array('id' => $atalaparrafoa->getId()));
            }

            return $this->render('atalaparrafoa/edit.html.twig', array(
                'atalaparrafoa' => $atalaparrafoa,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Atalaparrafoa entity.
     *
     * @Route("/{id}", name="atalaparrafoa_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Atalaparrafoa $atalaparrafoa)
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($atalaparrafoa->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {        
            $form = $this->createDeleteForm($atalaparrafoa);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($atalaparrafoa);
                $em->flush();
            }
            return $this->redirectToRoute('atalaparrafoa_index');
        }else
        {
            //baimenik ez
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a form to delete a Atalaparrafoa entity.
     *
     * @param Atalaparrafoa $atalaparrafoa The Atalaparrafoa entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Atalaparrafoa $atalaparrafoa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('atalaparrafoa_delete', array('id' => $atalaparrafoa->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
