<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Etiketa;
use App\Form\EtiketaType;
use App\Repository\EtiketaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Etiketa controller.
 */
#[Route(path: '/{_locale}/etiketa')]
class EtiketaController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em, 
        private EtiketaRepository $repo
    )
    {
    }

    /**
     * Lists all Etiketa entities.
     */
    #[IsGranted('ROLE_KUDEAKETA')]    
    #[Route(path: '/', defaults: ['page' => 1], name: 'etiketa_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'etiketa_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $etiketas = $this->repo->findBy( [], ['etiketaeu'=>'ASC'] );

        $deleteForms = [];
        foreach ($etiketas as $etiketa) {
            $deleteForms[$etiketa->getId()] = $this->createDeleteForm($etiketa)->createView();
        }

        return $this->render('etiketa/index.html.twig', ['etiketas' => $etiketas, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Etiketa entity.
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/new', name: 'etiketa_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {

        $etiketum = new Etiketa();
        $form = $this->createForm(EtiketaType::class, $etiketum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etiketum = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $etiketum->setUdala($user->getUdala());
            $this->em->persist($etiketum);
            $this->em->flush();

            return $this->redirectToRoute('etiketa_index');
        }

        return $this->render('etiketa/new.html.twig', ['etiketum' => $etiketum, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Etiketa entity.
     */
    #[Route(path: '/{id}', name: 'etiketa_show', methods: ['GET'])]
    public function show(Etiketa $etiketum): Response
    {
        $deleteForm = $this->createDeleteForm($etiketum);

        return $this->render('etiketa/show.html.twig', ['etiketum' => $etiketum, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Etiketa entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'etiketa_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etiketa $etiketum)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($etiketum->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($etiketum);
            $editForm = $this->createForm(EtiketaType::class, $etiketum);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($etiketum);
                $this->em->flush();
    
                return $this->redirectToRoute('etiketa_edit', ['id' => $etiketum->getId()]);
            }
    
            return $this->render('etiketa/edit.html.twig', ['etiketum' => $etiketum, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');
        }            
    }

    /**
     * Deletes a Etiketa entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'etiketa_delete', methods: ['DELETE'])]
    public function delete(Request $request, Etiketa $etiketum): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($etiketum->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($etiketum);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($etiketum);
                $this->em->flush();
            }
            return $this->redirectToRoute('etiketa_index');
        }else
        {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Creates a form to delete a Etiketa entity.
     *
     * @param Etiketa $etiketum The Etiketa entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Etiketa $etiketum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('etiketa_delete', ['id' => $etiketum->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
