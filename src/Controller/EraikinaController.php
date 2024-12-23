<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Eraikina;
use App\Form\EraikinaType;
use App\Repository\EraikinaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Eraikina controller.
 *
 * @Route("/{_locale}/eraikina")
 */
class EraikinaController extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, EraikinaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Eraikina entities.
     *
     * @Route("/", name="eraikina_index", methods={"GET"})
     * @Route("/", defaults={"page"=1}, name="eraikina_index", methods={"GET"})
     * @Route("/page{page}", name="eraikina_index_paginated", methods={"GET"})
     */
    public function index($page)
    {

        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $eraikinas = $this->repo->findAll();

            $deleteForms = [];
            foreach ($eraikinas as $eraikina) {
                $deleteForms[$eraikina->getId()] = $this->createDeleteForm($eraikina)->createView();
            }

            return $this->render('eraikina/index.html.twig', ['eraikinas' => $eraikinas, 'deleteforms' => $deleteForms]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Eraikina entity.
     *
     * @Route("/new", name="eraikina_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN'))
        {
            $eraikina = new Eraikina();
            $form = $this->createForm(EraikinaType::class, $eraikina);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($eraikina);
                $this->em->flush();

//                return $this->redirectToRoute('eraikina_show', array('id' => $eraikina->getId()));
                return $this->redirectToRoute('eraikina_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('eraikina/new.html.twig', ['eraikina' => $eraikina, 'form' => $form->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Eraikina entity.
     *
     * @Route("/{id}", name="eraikina_show", methods={"GET"})
     */
    public function show(Eraikina $eraikina): Response
    {
        $deleteForm = $this->createDeleteForm($eraikina);

        return $this->render('eraikina/show.html.twig', ['eraikina' => $eraikina, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Eraikina entity.
     *
     * @Route("/{id}/edit", name="eraikina_edit", methods={"GET", "POST"})
     * @param Request  $request
     * @param Eraikina $eraikina
     * @return Response
     */
    public function edit(Request $request, Eraikina $eraikina)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($eraikina->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($eraikina);
            $editForm = $this->createForm(EraikinaType::class, $eraikina);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($eraikina);
                $this->em->flush();
    
                return $this->redirectToRoute('eraikina_edit', ['id' => $eraikina->getId()]);
            }
    
            return $this->render('eraikina/edit.html.twig', ['eraikina' => $eraikina, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Deletes a Eraikina entity.
     *
     * @Route("/{id}", name="eraikina_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Eraikina $eraikina): RedirectResponse
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($eraikina->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($eraikina);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($eraikina);
                $this->em->flush();
            }
            return $this->redirectToRoute('eraikina_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a form to delete a Eraikina entity.
     *
     * @param Eraikina $eraikina The Eraikina entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Eraikina $eraikina)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('eraikina_delete', ['id' => $eraikina->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
