<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Dokumentumota;
use App\Form\DokumentumotaType;
use App\Repository\DokumentumotaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Dokumentumota controller.
 *
 * @Route("/{_locale}/dokumentumota")
 */
class DokumentumotaController extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, DokumentumotaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Dokumentumota entities.
     *
     * @Route("/", defaults={"page"=1}, name="dokumentumota_index", methods={"GET"})
     * @Route("/page{page}", name="dokumentumota_index_paginated", methods={"GET"})
     */
    public function index($page)
    {

        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $dokumentumotas = $this->repo->findBy( [], ['kodea'=>'ASC'] );

            $adapter = new ArrayAdapter($dokumentumotas);
            $pagerfanta = new Pagerfanta($adapter);            
            
            $deleteForms = [];
            foreach ($dokumentumotas as $dokumentumota) {
                $deleteForms[$dokumentumota->getId()] = $this->createDeleteForm($dokumentumota)->createView();
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

            return $this->render('dokumentumota/index.html.twig', ['dokumentumotas' => $entities, 'deleteforms' => $deleteForms, 'pager' => $pagerfanta]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Dokumentumota entity.
     *
     * @Route("/new", name="dokumentumota_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN'))
        {
            $dokumentumotum = new Dokumentumota();
            $form = $this->createForm(DokumentumotaType::class, $dokumentumotum);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($dokumentumotum);
                $this->em->flush();
//                return $this->redirectToRoute('dokumentumota_show', array('id' => $dokumentumotum->getId()));
                return $this->redirectToRoute('dokumentumota_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('dokumentumota/new.html.twig', ['dokumentumotum' => $dokumentumotum, 'form' => $form->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Dokumentumota entity.
     *
     * @Route("/{id}", name="dokumentumota_show", methods={"GET"})
     */
    public function show(Dokumentumota $dokumentumotum): Response
    {
        $deleteForm = $this->createDeleteForm($dokumentumotum);

        return $this->render('dokumentumota/show.html.twig', ['dokumentumotum' => $dokumentumotum, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Dokumentumota entity.
     *
     * @Route("/{id}/edit", name="dokumentumota_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Dokumentumota $dokumentumotum)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($dokumentumotum->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($dokumentumotum);
            $editForm = $this->createForm(DokumentumotaType::class, $dokumentumotum);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($dokumentumotum);
                $this->em->flush();
    
                return $this->redirectToRoute('dokumentumota_edit', ['id' => $dokumentumotum->getId()]);
            }
    
            return $this->render('dokumentumota/edit.html.twig', ['dokumentumotum' => $dokumentumotum, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Deletes a Dokumentumota entity.
     *
     * @Route("/{id}", name="dokumentumota_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Dokumentumota $dokumentumotum): RedirectResponse
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($dokumentumotum->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($dokumentumotum);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($dokumentumotum);
                $this->em->flush();
            }
            return $this->redirectToRoute('dokumentumota_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');            
        }
    }

    /**
     * Creates a form to delete a Dokumentumota entity.
     *
     * @param Dokumentumota $dokumentumotum The Dokumentumota entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Dokumentumota $dokumentumotum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('dokumentumota_delete', ['id' => $dokumentumotum->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
