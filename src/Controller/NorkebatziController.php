<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Norkebatzi;
use App\Form\NorkebatziType;
use App\Repository\NorkebatziRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * Norkebatzi controller.
 *
 * @Route("/{_locale}/norkebatzi")
 */
class NorkebatziController extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, NorkebatziRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Norkebatzi entities.
     *
     * @Route("/", defaults={"page" = 1}, name="norkebatzi_index")
     * @Route("/page{page}", name="norkebatzi_index_paginated")
     * @Method("GET")
     */
    public function indexAction($page)
    {

        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $norkebatzis = $this->repo->findAll();

            $deleteForms = array();
            foreach ($norkebatzis as $norkebatzi) {
                $deleteForms[$norkebatzi->getId()] = $this->createDeleteForm($norkebatzi)->createView();
            }

            return $this->render('norkebatzi/index.html.twig', array(
                'norkebatzis' => $norkebatzis,
                'deleteforms' => $deleteForms
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Norkebatzi entity.
     *
     * @Route("/new", name="norkebatzi_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN'))
        {
            $norkebatzi = new Norkebatzi();
            $form = $this->createForm(NorkebatziType::class, $norkebatzi);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($norkebatzi);
                $this->em->flush();

//                return $this->redirectToRoute('norkebatzi_show', array('id' => $norkebatzi->getId()));
                return $this->redirectToRoute('norkebatzi_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('norkebatzi/new.html.twig', array(
                'norkebatzi' => $norkebatzi,
                'form' => $form->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Norkebatzi entity.
     *
     * @Route("/{id}", name="norkebatzi_show")
     * @Method("GET")
     */
    public function showAction(Norkebatzi $norkebatzi)
    {
        $deleteForm = $this->createDeleteForm($norkebatzi);

        return $this->render('norkebatzi/show.html.twig', array(
            'norkebatzi' => $norkebatzi,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Norkebatzi entity.
     *
     * @Route("/{id}/edit", name="norkebatzi_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Norkebatzi $norkebatzi)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($norkebatzi->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($norkebatzi);
            $editForm = $this->createForm(NorkebatziType::class, $norkebatzi);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($norkebatzi);
                $this->em->flush();

                return $this->redirectToRoute('norkebatzi_edit', array('id' => $norkebatzi->getId()));
            }

            return $this->render('norkebatzi/edit.html.twig', array(
                'norkebatzi' => $norkebatzi,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Norkebatzi entity.
     *
     * @Route("/{id}", name="norkebatzi_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Norkebatzi $norkebatzi)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($norkebatzi->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($norkebatzi);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($norkebatzi);
                $this->em->flush();
            }
            return $this->redirectToRoute('norkebatzi_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Creates a form to delete a Norkebatzi entity.
     *
     * @param Norkebatzi $norkebatzi The Norkebatzi entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Norkebatzi $norkebatzi)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('norkebatzi_delete', array('id' => $norkebatzi->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
