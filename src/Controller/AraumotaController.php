<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Araumota;
use App\Form\AraumotaType;
use App\Repository\AraumotaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * Araumota controller.
 */
#[Route(path: '/{_locale}/araumota')]
class AraumotaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private AraumotaRepository $repo
    )
    {
    }

    /**
     * Lists all Araumota entities.
     */
    #[IsGranted("ROLE_KUDEAKETA")]
    #[Route(path: '/', defaults: ['page' => 1], name: 'araumota_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'araumota_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $araumotas = $this->repo->findBy( [], ['kodea'=>'ASC'] );
        $adapter = new ArrayAdapter($araumotas);
        $pagerfanta = new Pagerfanta($adapter);

        $deleteForms = [];
        foreach ($araumotas as $araumota) {
            $deleteForms[$araumota->getId()] = $this->createDeleteForm($araumota)->createView();
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
            throw $this->createNotFoundException("Cette page n'existe pas.");
        }

        return $this->render('araumota/index.html.twig', ['araumotas' => $entities, 'deleteforms' => $deleteForms, 'pager' => $pagerfanta]);
    }
    /**
     * Creates a new Araumota entity.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/new', name: 'araumota_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $araumotum = new Araumota();
        $form = $this->createForm(AraumotaType::class, $araumotum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $araumotum = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $araumotum->setUdala($udala);
            $this->em->persist($araumotum);
            $this->em->flush();

            return $this->redirectToRoute('araumota_index');
        }

        return $this->render('araumota/new.html.twig', ['araumotum' => $araumotum, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Araumota entity.
     */
    #[Route(path: '/{id}', name: 'araumota_show', methods: ['GET'])]
    public function show(Araumota $araumotum): Response
    {
        $deleteForm = $this->createDeleteForm($araumotum);

        return $this->render('araumota/show.html.twig', ['araumotum' => $araumotum, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Araumota entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'araumota_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Araumota $araumotum)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($araumotum->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($araumotum);
            $editForm = $this->createForm(AraumotaType::class, $araumotum);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($araumotum);
                $this->em->flush();

                return $this->redirectToRoute('araumota_edit', ['id' => $araumotum->getId()]);
            }

            return $this->render('araumota/edit.html.twig', ['araumotum' => $araumotum, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Araumota entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'araumota_delete', methods: ['DELETE'])]
    public function delete(Request $request, Araumota $araumotum): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($araumotum->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($araumotum);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($araumotum);
                $this->em->flush();
            }
            return $this->redirectToRoute('araumota_index');
        }else
        {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Creates a form to delete a Araumota entity.
     *
     * @param Araumota $araumotum The Araumota entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Araumota $araumotum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('araumota_delete', ['id' => $araumotum->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
