<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Dokumentazioa;
use App\Form\DokumentazioaType;
use App\Repository\DokumentazioaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * Dokumentazioa controller.
 */
#[Route(path: '/{_locale}/dokumentazioa')]
class DokumentazioaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private DokumentazioaRepository $repo
    )
    {
    }

    /**
     * Lists all Dokumentazioa entities.
     */
    #[IsGranted("ROLE_KUDEAKETA")]
    #[Route(path: '/', defaults: ['page' => 1], name: 'dokumentazioa_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'dokumentazioa_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $dokumentazioas = $this->repo->findBy( [], ['kodea'=>'ASC'] );

        $deleteForms = [];
        foreach ($dokumentazioas as $dokumentazioa) {
            $deleteForms[$dokumentazioa->getId()] = $this->createDeleteForm($dokumentazioa)->createView();
        }

        return $this->render('dokumentazioa/index.html.twig', ['dokumentazioas' => $dokumentazioas, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Dokumentazioa entity.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/new', name: 'dokumentazioa_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $dokumentazioa = new Dokumentazioa();
        $form = $this->createForm(DokumentazioaType::class, $dokumentazioa);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dokumentazioa = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $dokumentazioa->setUdala($udala);
            $this->em->persist($dokumentazioa);
            $this->em->flush();
            return $this->redirectToRoute('dokumentazioa_index');
        }

        return $this->render('dokumentazioa/new.html.twig', ['dokumentazioa' => $dokumentazioa, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Dokumentazioa entity.
     */
    #[Route(path: '/{id}', name: 'dokumentazioa_show', methods: ['GET'])]
    public function show(Dokumentazioa $dokumentazioa): Response
    {
        $deleteForm = $this->createDeleteForm($dokumentazioa);

        return $this->render('dokumentazioa/show.html.twig', ['dokumentazioa' => $dokumentazioa, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Dokumentazioa entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'dokumentazioa_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dokumentazioa $dokumentazioa)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($dokumentazioa->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($dokumentazioa);
            $editForm = $this->createForm(DokumentazioaType::class, $dokumentazioa);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($dokumentazioa);
                $this->em->flush();

                return $this->redirectToRoute('dokumentazioa_edit', ['id' => $dokumentazioa->getId()]);
            }

            return $this->render('dokumentazioa/edit.html.twig', ['dokumentazioa' => $dokumentazioa, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Dokumentazioa entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'dokumentazioa_delete', methods: ['DELETE'])]
    public function delete(Request $request, Dokumentazioa $dokumentazioa): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($dokumentazioa->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($dokumentazioa);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($dokumentazioa);
                $this->em->flush();
            }
            return $this->redirectToRoute('dokumentazioa_index');
        }else
        {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Creates a form to delete a Dokumentazioa entity.
     *
     * @param Dokumentazioa $dokumentazioa The Dokumentazioa entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Dokumentazioa $dokumentazioa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('dokumentazioa_delete', ['id' => $dokumentazioa->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
