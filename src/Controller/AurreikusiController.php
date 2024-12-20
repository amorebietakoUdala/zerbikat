<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Aurreikusi;
use App\Form\AurreikusiType;
use App\Repository\AurreikusiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Aurreikusi controller.
 *
 * @Route("/{_locale}/aurreikusi")
 */
class AurreikusiController extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, AurreikusiRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Aurreikusi entities.
     *
     * @Route("/", defaults={"page" = 1}, name="aurreikusi_index")
     * @Route("/page{page}", name="aurreikusi_index_paginated")
     * @Method("GET")
     */
    public function index($page)
    {
        if ($this->isGranted('ROLE_KUDEAKETA'))
        {
            $aurreikusis = $this->repo->findAll();

            $adapter = new ArrayAdapter($aurreikusis);
            $pagerfanta = new Pagerfanta($adapter);

            $deleteForms = [];
            foreach ($aurreikusis as $aurreikusi) {
                $deleteForms[$aurreikusi->getId()] = $this->createDeleteForm($aurreikusi)->createView();
            }

            try {
                $entities = $pagerfanta
                    // Le nombre maximum d'éléments par page
//                    ->setMaxPerPage($this->getUser()->getUdala()->getOrrikatzea())
                    // Notre position actuelle (numéro de page)
                    ->setCurrentPage($page)
                    // On récupère nos entités via Pagerfanta,
                    // celui-ci s'occupe de limiter la requête en fonction de nos réglages.
                    ->getCurrentPageResults()
                ;
            } catch (\Pagerfanta\Exception\NotValidCurrentPageException $e) {
                throw $this->createNotFoundException("Orria ez da existitzen");
            }

            return $this->render('aurreikusi/index.html.twig', ['aurreikusis' => $entities, 'deleteforms' => $deleteForms, 'pager' => $pagerfanta]);
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');            
        }
    }

    /**
     * Creates a new Aurreikusi entity.
     *
     * @Route("/new", name="aurreikusi_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        if ($this->isGranted('ROLE_ADMIN'))
        {
            $aurreikusi = new Aurreikusi();
            $form = $this->createForm(AurreikusiType::class, $aurreikusi);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($aurreikusi);
                $this->em->flush();

//                return $this->redirectToRoute('aurreikusi_show', array('id' => $aurreikusi->getId()));
                return $this->redirectToRoute('aurreikusi_index');                
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('aurreikusi/new.html.twig', ['aurreikusi' => $aurreikusi, 'form' => $form->createView()]);
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Aurreikusi entity.
     *
     * @Route("/{id}", name="aurreikusi_show")
     * @Method("GET")
     */
    public function show(Aurreikusi $aurreikusi): Response
    {
        $deleteForm = $this->createDeleteForm($aurreikusi);

        return $this->render('aurreikusi/show.html.twig', ['aurreikusi' => $aurreikusi, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Aurreikusi entity.
     *
     * @Route("/{id}/edit", name="aurreikusi_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Aurreikusi $aurreikusi)
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($aurreikusi->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($aurreikusi);
            $editForm = $this->createForm(AurreikusiType::class, $aurreikusi);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($aurreikusi);
                $this->em->flush();

                return $this->redirectToRoute('aurreikusi_edit', ['id' => $aurreikusi->getId()]);
            }

            return $this->render('aurreikusi/edit.html.twig', ['aurreikusi' => $aurreikusi, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Aurreikusi entity.
     *
     * @Route("/{id}", name="aurreikusi_delete")
     * @Method("DELETE")
     */
    public function delete(Request $request, Aurreikusi $aurreikusi): RedirectResponse
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($aurreikusi->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($aurreikusi);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($aurreikusi);
                $this->em->flush();
            }
            return $this->redirectToRoute('aurreikusi_index');
        }else
        {
            //baimenik ez
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');            
        }
    }

    /**
     * Creates a form to delete a Aurreikusi entity.
     *
     * @param Aurreikusi $aurreikusi The Aurreikusi entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Aurreikusi $aurreikusi)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('aurreikusi_delete', ['id' => $aurreikusi->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
