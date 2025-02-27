<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Eraikina;
use App\Form\EraikinaType;
use App\Repository\EraikinaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Security;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * Eraikina controller.
 */
#[Route(path: '/{_locale}/eraikina')]
class EraikinaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private EraikinaRepository $repo
    )
    {
    }

    /**
     * Lists all Eraikina entities.
     */
    #[IsGranted("ROLE_KUDEAKETA")]    
    #[Route(path: '/', defaults: ['page' => 1], name: 'eraikina_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'eraikina_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $eraikinas = $this->repo->findAll();

        $deleteForms = [];
        foreach ($eraikinas as $eraikina) {
            $deleteForms[$eraikina->getId()] = $this->createDeleteForm($eraikina)->createView();
        }

        return $this->render('eraikina/index.html.twig', ['eraikinas' => $eraikinas, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Eraikina entity.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/new', name: 'eraikina_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $eraikina = new Eraikina();
        $form = $this->createForm(EraikinaType::class, $eraikina);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eraikina = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $eraikina->setUdala($udala);
            $this->em->persist($eraikina);
            $this->em->flush();
            return $this->redirectToRoute('eraikina_index');
        }

        return $this->render('eraikina/new.html.twig', ['eraikina' => $eraikina, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Eraikina entity.
     */
    #[Route(path: '/{id}', name: 'eraikina_show', methods: ['GET'])]
    public function show(Eraikina $eraikina): Response
    {
        $deleteForm = $this->createDeleteForm($eraikina);

        return $this->render('eraikina/show.html.twig', ['eraikina' => $eraikina, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Eraikina entity.
     *
     * @param Request  $request
     * @param Eraikina $eraikina
     * @return Response
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'eraikina_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Eraikina $eraikina)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($eraikina->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($eraikina);
            $editForm = $this->createForm(EraikinaType::class, $eraikina);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($eraikina);
                $this->em->flush();
    
                return $this->redirectToRoute('eraikina_edit', ['id' => $eraikina->getId()]);
            }
    
            return $this->render('eraikina/edit.html.twig', ['eraikina' => $eraikina, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');
        }            
    }

    /**
     * Deletes a Eraikina entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'eraikina_delete', methods: ['DELETE'])]
    public function delete(Request $request, Eraikina $eraikina): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($eraikina->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($eraikina);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($eraikina);
                $this->em->flush();
            }
            return $this->redirectToRoute('eraikina_index');
        }else
        {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Creates a form to delete a Eraikina entity.
     *
     * @param Eraikina $eraikina The Eraikina entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Eraikina $eraikina)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('eraikina_delete', ['id' => $eraikina->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
