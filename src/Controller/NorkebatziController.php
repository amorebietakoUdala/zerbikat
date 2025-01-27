<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Norkebatzi;
use App\Form\NorkebatziType;
use App\Repository\NorkebatziRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

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
     * @Route("/", defaults={"page"=1}, name="norkebatzi_index", methods={"GET"})
     * @Route("/page{page}", name="norkebatzi_index_paginated", methods={"GET"})
     */
    public function index($page)
    {

        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $norkebatzis = $this->repo->findAll();

            $deleteForms = [];
            foreach ($norkebatzis as $norkebatzi) {
                $deleteForms[$norkebatzi->getId()] = $this->createDeleteForm($norkebatzi)->createView();
            }

            return $this->render('norkebatzi/index.html.twig', ['norkebatzis' => $norkebatzis, 'deleteforms' => $deleteForms]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Norkebatzi entity.
     *
     * @Route("/new", name="norkebatzi_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
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

            return $this->render('norkebatzi/new.html.twig', ['norkebatzi' => $norkebatzi, 'form' => $form->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Norkebatzi entity.
     *
     * @Route("/{id}", name="norkebatzi_show", methods={"GET"})
     */
    public function show(Norkebatzi $norkebatzi): Response
    {
        $deleteForm = $this->createDeleteForm($norkebatzi);

        return $this->render('norkebatzi/show.html.twig', ['norkebatzi' => $norkebatzi, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Norkebatzi entity.
     *
     * @Route("/{id}/edit", name="norkebatzi_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Norkebatzi $norkebatzi)
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

                return $this->redirectToRoute('norkebatzi_edit', ['id' => $norkebatzi->getId()]);
            }

            return $this->render('norkebatzi/edit.html.twig', ['norkebatzi' => $norkebatzi, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Norkebatzi entity.
     *
     * @Route("/{id}", name="norkebatzi_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Norkebatzi $norkebatzi): RedirectResponse
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
     * @return Form The form
     */
    private function createDeleteForm(Norkebatzi $norkebatzi)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('norkebatzi_delete', ['id' => $norkebatzi->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
