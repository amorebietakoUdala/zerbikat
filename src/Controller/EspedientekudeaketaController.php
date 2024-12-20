<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Espedientekudeaketa;
use App\Form\EspedientekudeaketaType;
use App\Repository\EspedientekudeaketaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Espedientekudeaketa controller.
 *
 * @Route("/{_locale}/espedientekudeaketa")
 */
class EspedientekudeaketaController extends AbstractController
{
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, EspedientekudeaketaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Espedientekudeaketa entities.
     *
     * @Route("/", defaults={"page" = 1}, name="espedientekudeaketa_index")
     * @Route("/page{page}", name="espedientekudeaketa_index_paginated")
     * @Method("GET")
     */
    public function index($page)
    {

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $espedientekudeaketas = $this->repo->findAll();

            $adapter = new ArrayAdapter($espedientekudeaketas);
            $pagerfanta = new Pagerfanta($adapter);

            $deleteForms = [];
            foreach ($espedientekudeaketas as $espedientekudeaketa) {
                $deleteForms[$espedientekudeaketa->getId()] = $this->createDeleteForm($espedientekudeaketa)->createView();
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

            return $this->render('espedientekudeaketa/index.html.twig', ['espedientekudeaketas' => $entities, 'deleteforms' => $deleteForms, 'pager' => $pagerfanta]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Espedientekudeaketa entity.
     *
     * @Route("/new", name="espedientekudeaketa_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {

        if ($this->isGranted('ROLE_SUPER_ADMIN'))
        {
            $espedientekudeaketum = new Espedientekudeaketa();
            $form = $this->createForm(EspedientekudeaketaType::class, $espedientekudeaketum);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($espedientekudeaketum);
                $this->em->flush();

//                return $this->redirectToRoute('espedientekudeaketa_show', array('id' => $espedientekudeaketum->getId()));
                return $this->redirectToRoute('espedientekudeaketa_index');
            }

            return $this->render('espedientekudeaketa/new.html.twig', ['espedientekudeaketum' => $espedientekudeaketum, 'form' => $form->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Espedientekudeaketa entity.
     *
     * @Route("/{id}", name="espedientekudeaketa_show")
     * @Method("GET")
     */
    public function show(Espedientekudeaketa $espedientekudeaketum): Response
    {
        $deleteForm = $this->createDeleteForm($espedientekudeaketum);

        return $this->render('espedientekudeaketa/show.html.twig', ['espedientekudeaketum' => $espedientekudeaketum, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Espedientekudeaketa entity.
     *
     * @Route("/{id}/edit", name="espedientekudeaketa_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Espedientekudeaketa $espedientekudeaketum)
    {

        if ($this->isGranted('ROLE_SUPER_ADMIN'))
        {
            $deleteForm = $this->createDeleteForm($espedientekudeaketum);
            $editForm = $this->createForm(EspedientekudeaketaType::class, $espedientekudeaketum);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($espedientekudeaketum);
                $this->em->flush();

                return $this->redirectToRoute('espedientekudeaketa_edit', ['id' => $espedientekudeaketum->getId()]);
            }

            return $this->render('espedientekudeaketa/edit.html.twig', ['espedientekudeaketum' => $espedientekudeaketum, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Espedientekudeaketa entity.
     *
     * @Route("/{id}", name="espedientekudeaketa_delete")
     * @Method("DELETE")
     */
    public function delete(Request $request, Espedientekudeaketa $espedientekudeaketum): RedirectResponse
    {

        if($this->isGranted('ROLE_SUPER_ADMIN'))
        {
            $form = $this->createDeleteForm($espedientekudeaketum);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($espedientekudeaketum);
                $this->em->flush();
            }
            return $this->redirectToRoute('espedientekudeaketa_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');            
        }
    }

    /**
     * Creates a form to delete a Espedientekudeaketa entity.
     *
     * @param Espedientekudeaketa $espedientekudeaketum The Espedientekudeaketa entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Espedientekudeaketa $espedientekudeaketum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('espedientekudeaketa_delete', ['id' => $espedientekudeaketum->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
