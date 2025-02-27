<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Aurreikusi;
use App\Form\AurreikusiType;
use App\Repository\AurreikusiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Aurreikusi controller.
 */
#[Route(path: '/{_locale}/aurreikusi')]
class AurreikusiController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private AurreikusiRepository $repo
    )
    {
    }

    /**
     * Lists all Aurreikusi entities.
     */
    #[IsGranted("ROLE_KUDEAKETA")]
    #[Route(path: '/', defaults: ['page' => 1], name: 'aurreikusi_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'aurreikusi_index_paginated', methods: ['GET'])]
    public function index($page)
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
        } catch (NotValidCurrentPageException) {
            throw $this->createNotFoundException("Orria ez da existitzen");
        }

        return $this->render('aurreikusi/index.html.twig', ['aurreikusis' => $entities, 'deleteforms' => $deleteForms, 'pager' => $pagerfanta]);
    }

    /**
     * Creates a new Aurreikusi entity.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/new', name: 'aurreikusi_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $aurreikusi = new Aurreikusi();
        $form = $this->createForm(AurreikusiType::class, $aurreikusi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $aurreikusi = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $aurreikusi->setUdala($udala);            
            $this->em->persist($aurreikusi);
            $this->em->flush();

            return $this->redirectToRoute('aurreikusi_index');                
        }

        return $this->render('aurreikusi/new.html.twig', ['aurreikusi' => $aurreikusi, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Aurreikusi entity.
     */
    #[Route(path: '/{id}', name: 'aurreikusi_show', methods: ['GET'])]
    public function show(Aurreikusi $aurreikusi): Response
    {
        $deleteForm = $this->createDeleteForm($aurreikusi);

        return $this->render('aurreikusi/show.html.twig', ['aurreikusi' => $aurreikusi, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Aurreikusi entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'aurreikusi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Aurreikusi $aurreikusi)
    {
        /** @var User $user */
        $user = $this->getUser();        
        if((($this->isGranted('ROLE_ADMIN')) && ($aurreikusi->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');         
}
    }

    /**
     * Deletes a Aurreikusi entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'aurreikusi_delete', methods: ['DELETE'])]
    public function delete(Request $request, Aurreikusi $aurreikusi): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();        
        if((($this->isGranted('ROLE_ADMIN')) && ($aurreikusi->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');         
        }
    }

    /**
     * Creates a form to delete a Aurreikusi entity.
     *
     * @param Aurreikusi $aurreikusi The Aurreikusi entity
     *
     * @return Form The form
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
