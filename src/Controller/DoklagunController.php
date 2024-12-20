<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Doklagun;
use App\Form\DoklagunType;
use App\Repository\DoklagunRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * Doklagun controller.
 *
 * @Route("/{_locale}/doklagun")
 */
class DoklagunController extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, DoklagunRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Doklagun entities.
     *
     * @Route("/", defaults={"page" = 1}, name="doklagun_index")
     * @Route("/page{page}", name="doklagun_index_paginated")
     * @Method("GET")
     */
    public function indexAction($page)
    {

        if ($this->isGranted('ROLE_KUDEAKETA'))
        {
            $doklaguns = $this->repo->findBy( array(), array('kodea'=>'ASC') );

            $deleteForms = array();
            foreach ($doklaguns as $doklagun) {
                $deleteForms[$doklagun->getId()] = $this->createDeleteForm($doklagun)->createView();
            }

            return $this->render('doklagun/index.html.twig', array(
                'doklaguns' => $doklaguns,
                'deleteforms' => $deleteForms
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Doklagun entity.
     *
     * @Route("/new", name="doklagun_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN'))
        {
            $doklagun = new Doklagun();
            $form = $this->createForm(DoklagunType::class, $doklagun);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($doklagun);
                $this->em->flush();

//                return $this->redirectToRoute('doklagun_show', array('id' => $doklagun->getId()));
                return $this->redirectToRoute('doklagun_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('doklagun/new.html.twig', array(
                'doklagun' => $doklagun,
                'form' => $form->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Doklagun entity.
     *
     * @Route("/{id}", name="doklagun_show")
     * @Method("GET")
     */
    public function showAction(Doklagun $doklagun)
    {
        $deleteForm = $this->createDeleteForm($doklagun);

        return $this->render('doklagun/show.html.twig', array(
            'doklagun' => $doklagun,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Doklagun entity.
     *
     * @Route("/{id}/edit", name="doklagun_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Doklagun $doklagun)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($doklagun->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($doklagun);
            $editForm = $this->createForm(DoklagunType::class, $doklagun);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($doklagun);
                $this->em->flush();

                return $this->redirectToRoute('doklagun_edit', array('id' => $doklagun->getId()));
            }

            return $this->render('doklagun/edit.html.twig', array(
                'doklagun' => $doklagun,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Doklagun entity.
     *
     * @Route("/{id}", name="doklagun_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Doklagun $doklagun)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($doklagun->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($doklagun);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($doklagun);
                $this->em->flush();
            }

            return $this->redirectToRoute('doklagun_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a form to delete a Doklagun entity.
     *
     * @param Doklagun $doklagun The Doklagun entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Doklagun $doklagun)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('doklagun_delete', array('id' => $doklagun->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
