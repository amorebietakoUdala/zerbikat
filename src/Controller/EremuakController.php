<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Eremuak;
use App\Form\EremuakType;
use App\Repository\EremuakRepository;
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
 * Eremuak controller.
 */
#[Route(path: '/{_locale}/eremuak')]
class EremuakController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em, 
        private EremuakRepository $repo
    )
    {
    }

    /**
     * Lists all Eremuak entities.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/', defaults: ['page' => 1], name: 'eremuak_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'eremuak_index_paginated', methods: ['GET'])]
    public function index($page)
    {

        if ($this->isGranted('ROLE_SUPER_ADMIN'))
        {
            $eremuaks = $this->repo->findAll();

            $adapter = new ArrayAdapter($eremuaks);
            $pagerfanta = new Pagerfanta($adapter);

            $deleteForms = [];
            foreach ($eremuaks as $eremuak) {
                $deleteForms[$eremuak->getId()] = $this->createDeleteForm($eremuak)->createView();
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

            return $this->render('eremuak/index.html.twig', ['eremuaks' => $entities, 'deleteforms' => $deleteForms, 'pager' => $pagerfanta]);
        }else if ($this->isGranted('ROLE_ADMIN'))
        {
            /** @var User $user */
            $user = $this->getUser();
            $udala=$user->getUdala()->getId();
            $query = $this->em->createQuery('
              SELECT f.id
                FROM App:Eremuak f
                WHERE f.udala = :udala
              ');
            $query->setParameter('udala', $udala);
            $eremuid = $query->getSingleResult();

            $eremuak=$user->getUdala()->getEremuak();

            return $this->redirectToRoute('eremuak_edit', ['id' => $eremuid['id']]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Creates a new Eremuak entity.
     */
    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route(path: '/new', name: 'eremuak_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {

        $eremuak = new Eremuak();
        $form = $this->createForm(EremuakType::class, $eremuak);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($eremuak);
            $this->em->flush();

            return $this->redirectToRoute('eremuak_index');
        }

        return $this->render('eremuak/new.html.twig', ['eremuak' => $eremuak, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Eremuak entity.
     */
    #[Route(path: '/{id}', name: 'eremuak_show', methods: ['GET'])]
    public function show(Eremuak $eremuak): Response
    {
        $deleteForm = $this->createDeleteForm($eremuak);

        return $this->render('eremuak/show.html.twig', ['eremuak' => $eremuak, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Eremuak entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'eremuak_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Eremuak $eremuak)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($eremuak->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($eremuak);
            $editForm = $this->createForm(EremuakType::class, $eremuak);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($eremuak);
                $this->em->flush();

                return $this->redirectToRoute('eremuak_edit', ['id' => $eremuak->getId()]);
            }

            return $this->render('eremuak/edit.html.twig', ['eremuak' => $eremuak, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Eremuak entity.
     */
    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route(path: '/{id}', name: 'eremuak_delete', methods: ['DELETE'])]
    public function delete(Request $request, Eremuak $eremuak): RedirectResponse
    {
        $form = $this->createDeleteForm($eremuak);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->remove($eremuak);
            $this->em->flush();
        }
        return $this->redirectToRoute('eremuak_index');
    }

    /**
     * Creates a form to delete a Eremuak entity.
     *
     * @param Eremuak $eremuak The Eremuak entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Eremuak $eremuak)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('eremuak_delete', ['id' => $eremuak->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
