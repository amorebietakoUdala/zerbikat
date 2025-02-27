<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Norkebatzi;
use App\Form\NorkebatziType;
use App\Repository\NorkebatziRepository;
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
 * Norkebatzi controller.
 */
#[Route(path: '/{_locale}/norkebatzi')]
class NorkebatziController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private NorkebatziRepository $repo
    )
    {
    }

    /**
     * Lists all Norkebatzi entities.
     */
    #[IsGranted('ROLE_KUDEAKETA')]
    #[Route(path: '/', defaults: ['page' => 1], name: 'norkebatzi_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'norkebatzi_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $norkebatzis = $this->repo->findAll();

        $deleteForms = [];
        foreach ($norkebatzis as $norkebatzi) {
            $deleteForms[$norkebatzi->getId()] = $this->createDeleteForm($norkebatzi)->createView();
        }

        return $this->render('norkebatzi/index.html.twig', ['norkebatzis' => $norkebatzis, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Norkebatzi entity.
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/new', name: 'norkebatzi_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $norkebatzi = new Norkebatzi();
        $form = $this->createForm(NorkebatziType::class, $norkebatzi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $norkebatzi = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $norkebatzi->setUdala($udala);                
            $this->em->persist($norkebatzi);
            $this->em->flush();

            return $this->redirectToRoute('norkebatzi_index');
        }

        return $this->render('norkebatzi/new.html.twig', ['norkebatzi' => $norkebatzi, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Norkebatzi entity.
     */
    #[Route(path: '/{id}', name: 'norkebatzi_show', methods: ['GET'])]
    public function show(Norkebatzi $norkebatzi): Response
    {
        $deleteForm = $this->createDeleteForm($norkebatzi);

        return $this->render('norkebatzi/show.html.twig', ['norkebatzi' => $norkebatzi, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Norkebatzi entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'norkebatzi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Norkebatzi $norkebatzi)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($norkebatzi->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($norkebatzi);
            $editForm = $this->createForm(NorkebatziType::class, $norkebatzi);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($norkebatzi);
                $this->em->flush();

                return $this->redirectToRoute('norkebatzi_edit', ['id' => $norkebatzi->getId()]);
            }

            return $this->render('norkebatzi/edit.html.twig', ['norkebatzi' => $norkebatzi, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Norkebatzi entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'norkebatzi_delete', methods: ['DELETE'])]
    public function delete(Request $request, Norkebatzi $norkebatzi): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($norkebatzi->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($norkebatzi);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($norkebatzi);
                $this->em->flush();
            }
            return $this->redirectToRoute('norkebatzi_index');
        }else
        {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }            
    }

    /**
     * Creates a form to delete a Norkebatzi entity.
     *
     * @param Norkebatzi $norkebatzi The Norkebatzi entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Norkebatzi $norkebatzi)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('norkebatzi_delete', ['id' => $norkebatzi->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
