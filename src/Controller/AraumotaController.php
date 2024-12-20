<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Araumota;
use App\Form\AraumotaType;
use App\Repository\AraumotaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Araumota controller.
 *
 * @Route("/{_locale}/araumota")
 */
class AraumotaController extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, AraumotaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Araumota entities.
     *
     * @Route("/", defaults={"page" = 1}, name="araumota_index")
     * @Route("/page{page}", name="araumota_index_paginated")
     * @Method("GET")
     */
    public function index($page)
    {
        if ($this->isGranted('ROLE_KUDEAKETA')) 
        {
            $araumotas = $this->repo->findBy( [], ['kodea'=>'ASC'] );
            $adapter = new ArrayAdapter($araumotas);
            $pagerfanta = new Pagerfanta($adapter);

            $deleteForms = [];
            foreach ($araumotas as $araumota) {
                $deleteForms[$araumota->getId()] = $this->createDeleteForm($araumota)->createView();
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
                throw $this->createNotFoundException("Cette page n'existe pas.");
            }

            return $this->render('araumota/index.html.twig', ['araumotas' => $entities, 'deleteforms' => $deleteForms, 'pager' => $pagerfanta]);
        } else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }
    /**
     * Creates a new Araumota entity.
     *
     * @Route("/new", name="araumota_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        if ($this->isGranted('ROLE_ADMIN'))
        {
            $araumotum = new Araumota();
            $form = $this->createForm(AraumotaType::class, $araumotum);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($araumotum);
                $this->em->flush();

//                return $this->redirectToRoute('araumota_show', array('id' => $araumotum->getId()));
                return $this->redirectToRoute('araumota_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('araumota/new.html.twig', ['araumotum' => $araumotum, 'form' => $form->createView()]);
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Araumota entity.
     *
     * @Route("/{id}", name="araumota_show")
     * @Method("GET")
     */
    public function show(Araumota $araumotum): Response
    {
        $deleteForm = $this->createDeleteForm($araumotum);

        return $this->render('araumota/show.html.twig', ['araumotum' => $araumotum, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Araumota entity.
     *
     * @Route("/{id}/edit", name="araumota_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Araumota $araumotum)
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($araumotum->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($araumotum);
            $editForm = $this->createForm(AraumotaType::class, $araumotum);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($araumotum);
                $this->em->flush();

                return $this->redirectToRoute('araumota_edit', ['id' => $araumotum->getId()]);
            }

            return $this->render('araumota/edit.html.twig', ['araumotum' => $araumotum, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Araumota entity.
     *
     * @Route("/{id}", name="araumota_delete")
     * @Method("DELETE")
     */
    public function delete(Request $request, Araumota $araumotum): RedirectResponse
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($araumotum->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($araumotum);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($araumotum);
                $this->em->flush();
            }
            return $this->redirectToRoute('araumota_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a form to delete a Araumota entity.
     *
     * @param Araumota $araumotum The Araumota entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Araumota $araumotum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('araumota_delete', ['id' => $araumotum->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
