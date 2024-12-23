<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Arrunta;
use App\Form\ArruntaType;
use App\Repository\ArruntaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Arrunta controller.
 *
 * @Route("/{_locale}/arrunta")
 */
class ArruntaController extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, ArruntaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Arrunta entities.
     *
     * @Route("/", defaults={"page"=1}, name="arrunta_index", methods={"GET"})
     * @Route("/page{page}", name="arrunta_index_paginated", methods={"GET"})
     */
    public function index($page)
    {
        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $arruntas = $this->repo->findAll();

            $adapter = new ArrayAdapter($arruntas);
            $pagerfanta = new Pagerfanta($adapter);

            $deleteForms = [];
            foreach ($arruntas as $arrunta) {
                $deleteForms[$arrunta->getId()] = $this->createDeleteForm($arrunta)->createView();
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
            return $this->render('arrunta/index.html.twig', ['arruntas' => $entities, 'deleteforms' => $deleteForms, 'pager' => $pagerfanta]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');            
        }
    }

    /**
     * Creates a new Arrunta entity.
     *
     * @Route("/new", name="arrunta_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {
        if ($this->isGranted('ROLE_ADMIN'))
        {
            $arruntum = new Arrunta();
            $form = $this->createForm(ArruntaType::class, $arruntum);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($arruntum);
                $this->em->flush();

//                return $this->redirectToRoute('arrunta_show', array('id' => $arruntum->getId()));
                return $this->redirectToRoute('arrunta_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('arrunta/new.html.twig', ['arruntum' => $arruntum, 'form' => $form->createView()]);
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');            
        }
    }

    /**
     * Finds and displays a Arrunta entity.
     *
     * @Route("/{id}", name="arrunta_show", methods={"GET"})
     */
    public function show(Arrunta $arruntum): Response
    {
        $deleteForm = $this->createDeleteForm($arruntum);

        return $this->render('arrunta/show.html.twig', ['arruntum' => $arruntum, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Arrunta entity.
     *
     * @Route("/{id}/edit", name="arrunta_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Arrunta $arruntum)
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($arruntum->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($arruntum);
            $editForm = $this->createForm(ArruntaType::class, $arruntum);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($arruntum);
                $this->em->flush();
    
                return $this->redirectToRoute('arrunta_edit', ['id' => $arruntum->getId()]);
            }
    
            return $this->render('arrunta/edit.html.twig', ['arruntum' => $arruntum, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');            
        }
    }

    /**
     * Deletes a Arrunta entity.
     *
     * @Route("/{id}", name="arrunta_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Arrunta $arruntum): RedirectResponse
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($arruntum->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($arruntum);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($arruntum);
                $this->em->flush();
            }
            return $this->redirectToRoute('arrunta_index');
        }else
        {
            //baimenik ez
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');            
        }
    }

    /**
     * Creates a form to delete a Arrunta entity.
     *
     * @param Arrunta $arruntum The Arrunta entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Arrunta $arruntum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('arrunta_delete', ['id' => $arruntum->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
