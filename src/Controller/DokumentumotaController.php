<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Dokumentumota;
use App\Form\DokumentumotaType;
use App\Repository\DokumentumotaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * Dokumentumota controller.
 */
#[Route(path: '/{_locale}/dokumentumota')]
class DokumentumotaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private DokumentumotaRepository $repo
    )
    {
    }

    /**
     * Lists all Dokumentumota entities.
     */
    #[IsGranted("ROLE_KUDEAKETA")]
    #[Route(path: '/', defaults: ['page' => 1], name: 'dokumentumota_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'dokumentumota_index_paginated', methods: ['GET'])]
    public function index($page)
    {

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
        } catch (NotValidCurrentPageException) {
            throw $this->createNotFoundException("Orria ez da existitzen");
        }

        return $this->render('dokumentumota/index.html.twig', ['dokumentumotas' => $entities, 'deleteforms' => $deleteForms, 'pager' => $pagerfanta]);
    }

    /**
     * Creates a new Dokumentumota entity.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/new', name: 'dokumentumota_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $dokumentumotum = new Dokumentumota();
        $form = $this->createForm(DokumentumotaType::class, $dokumentumotum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dokumentumotum = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $dokumentumotum->setUdala($user->getUdala());
            $this->em->persist($dokumentumotum);
            $this->em->flush();

            return $this->redirectToRoute('dokumentumota_index');
        }

        return $this->render('dokumentumota/new.html.twig', ['dokumentumotum' => $dokumentumotum, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Dokumentumota entity.
     */
    #[Route(path: '/{id}', name: 'dokumentumota_show', methods: ['GET'])]
    public function show(Dokumentumota $dokumentumotum): Response
    {
        $deleteForm = $this->createDeleteForm($dokumentumotum);

        return $this->render('dokumentumota/show.html.twig', ['dokumentumotum' => $dokumentumotum, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Dokumentumota entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'dokumentumota_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dokumentumota $dokumentumotum)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($dokumentumotum->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');
        }            
    }

    /**
     * Deletes a Dokumentumota entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'dokumentumota_delete', methods: ['DELETE'])]
    public function delete(Request $request, Dokumentumota $dokumentumotum): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($dokumentumotum->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');            
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
