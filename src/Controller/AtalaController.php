<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Atala;
use App\Form\AtalaType;
use App\Repository\AtalaRepository;
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
 * Atala controller.
 */
#[Route(path: '/{_locale}/atala')]
class AtalaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private AtalaRepository $repo
    )
    {
    }

    /**
     * Lists all Atala entities.
     */
    #[IsGranted("ROLE_KUDEAKETA")]
    #[Route(path: '/', name: 'atala_index', methods: ['GET'])]
    #[Route(path: '/', defaults: ['page' => 1], name: 'atala_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'atala_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $atalas = $this->repo->findAll();

        $adapter = new ArrayAdapter($atalas);
        $pagerfanta = new Pagerfanta($adapter);

        $deleteForms = [];
        foreach ($atalas as $atala) {
            $deleteForms[$atala->getId()] = $this->createDeleteForm($atala)->createView();
        }
        try {
            $entities = $pagerfanta
                // Le nombre maximum d'éléments par page
                ->setMaxPerPage(20)
                // Notre position actuelle (numéro de page)
                ->setCurrentPage($page)
                // On récupère nos entités via Pagerfanta,
                // celui-ci s'occupe de limiter la requête en fonction de nos réglages.
                ->getCurrentPageResults()
            ;
        } catch (NotValidCurrentPageException) {
            throw $this->createNotFoundException("Orria ez da existitzen");
        }
        return $this->render('atala/index.html.twig', ['atalas' => $entities, 'deleteforms' => $deleteForms, 'pager' => $pagerfanta]);
    }

    /**
     * Creates a new Atala entity.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/new', name: 'atala_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $atala = new Atala();
        $form = $this->createForm(AtalaType::class, $atala);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $atala = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $atala->setUdala($udala);            
            $atala->setCreatedAt(new \DateTime());
            $atala->setUpdatedAt(new \DateTime());
            $this->em->persist($atala);
            $this->em->flush();

            return $this->redirectToRoute('atala_index');
        }

        return $this->render('atala/new.html.twig', ['atala' => $atala, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Atala entity.
     */
    #[Route(path: '/{id}', name: 'atala_show', methods: ['GET'])]
    public function show(Atala $atala): Response
    {
        $deleteForm = $this->createDeleteForm($atala);

        return $this->render('atala/show.html.twig', ['atala' => $atala, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Atala entity.
     */    
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]   
    #[Route(path: '/{id}/edit', name: 'atala_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Atala $atala)
    {
        /** @var User $user */
        $user = $this->getUser();        
        if((($this->isGranted('ROLE_ADMIN')) && ($atala->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($atala);
            $editForm = $this->createForm(AtalaType::class, $atala);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($atala);
                $this->em->flush();
    
                return $this->redirectToRoute('atala_edit', ['id' => $atala->getId()]);
            }
    
            return $this->render('atala/edit.html.twig', ['atala' => $atala, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');       
        }
    }

    /**
     * Deletes a Atala entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]    
    #[Route(path: '/{id}', name: 'atala_delete', methods: ['DELETE'])]
    public function delete(Request $request, Atala $atala): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($atala->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($atala);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($atala);
                $this->em->flush();
            }
            return $this->redirectToRoute('atala_index');
        }else
        {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Creates a form to delete a Atala entity.
     *
     * @param Atala $atala The Atala entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Atala $atala)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('atala_delete', ['id' => $atala->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
