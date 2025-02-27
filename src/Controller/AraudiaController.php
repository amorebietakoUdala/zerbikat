<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Araudia;
use App\Form\AraudiaType;
use App\Repository\AraudiaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\ExpressionLanguage\Expression;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Araudia controller.
 */
#[Route(path: '/{_locale}/araudia')]
class AraudiaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private AraudiaRepository $repo
    )
    {
    }

    /**
     * Lists all Araudia entities.
     */
    #[IsGranted("ROLE_KUDEAKETA")]
    #[Route(path: '/', defaults: ['page' => 1], name: 'araudia_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'araudia_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $araudias = $this->repo->findBy( [], ['kodea'=>'ASC'] );

        $deleteForms = [];
        foreach ($araudias as $araudia) {
            $deleteForms[$araudia->getId()] = $this->createDeleteForm($araudia)->createView();
        }

        return $this->render('araudia/index.html.twig', ['araudias' => $araudias, 'deleteforms' => $deleteForms]);
    }


    /**
     * Creates a new Araudia entity.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/new', name: 'araudia_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $araudium = new Araudia();
        $form = $this->createForm(AraudiaType::class, $araudium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $araudium = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $araudium->setUdala($udala);
            $this->em->persist($araudium);
            $this->em->flush();

            return $this->redirectToRoute('araudia_index');
        }

        return $this->render('araudia/new.html.twig', ['araudium' => $araudium, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Araudia entity.
     */
    #[Route(path: '/{id}', name: 'araudia_show', methods: ['GET'])]
    public function show(Araudia $araudium): Response
    {
        $deleteForm = $this->createDeleteForm($araudium);

        return $this->render('araudia/show.html.twig', ['araudium' => $araudium, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Araudia entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'araudia_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Araudia $araudium)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($araudium->getUdala()==$user->getUdala()))
            || ($this->isGranted('ROLE_SUPER_ADMIN'))
        )
        {
            $deleteForm = $this->createDeleteForm($araudium);
            $editForm = $this->createForm(AraudiaType::class, $araudium);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($araudium);
                $this->em->flush();

                return $this->redirectToRoute('araudia_edit', ['id' => $araudium->getId()]);
            }

            return $this->render('araudia/edit.html.twig', ['araudium' => $araudium, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Araudia entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'araudia_delete', methods: ['DELETE'])]
    public function delete(Request $request, Araudia $araudium): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($araudium->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($araudium);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($araudium);
                $this->em->flush();
            }
            return $this->redirectToRoute('araudia_index');
        }else
        {
            //baimenik ez
//            return $this->redirectToRoute('fitxa_index');
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Creates a form to delete a Araudia entity.
     *
     * @param Araudia $araudium The Araudia entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Araudia $araudium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('araudia_delete', ['id' => $araudium->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
