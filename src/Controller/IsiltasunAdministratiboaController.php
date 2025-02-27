<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\IsiltasunAdministratiboa;
use App\Form\IsiltasunAdministratiboaType;
use App\Repository\IsiltasunAdministratiboaRepository;
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
 * IsiltasunAdministratiboa controller.
 */
#[Route(path: '/{_locale}/isiltasunadministratiboa')]
class IsiltasunAdministratiboaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private IsiltasunAdministratiboaRepository $repo
    )
    {
    }

    /**
     * Lists all IsiltasunAdministratiboa entities.
     */
    #[IsGranted('ROLE_KUDEAKETA')]    
    #[Route(path: '/', name: 'isiltasunadministratiboa_index', methods: ['GET'])]
    #[Route(path: '/', defaults: ['page' => 1], name: 'isiltasunadministratiboa_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'isiltasunadministratiboa_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $isiltasunAdministratiboas = $this->repo->findAll();

        $adapter = new ArrayAdapter($isiltasunAdministratiboas);
        $pagerfanta = new Pagerfanta($adapter);
        
        $deleteForms = [];
        foreach ($isiltasunAdministratiboas as $isiltasunAdministratiboa) {
            $deleteForms[$isiltasunAdministratiboa->getId()] = $this->createDeleteForm($isiltasunAdministratiboa)->createView();
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
        
        return $this->render('isiltasunadministratiboa/index.html.twig', ['isiltasunAdministratiboas' => $entities, 'deleteforms' => $deleteForms, 'pager' => $pagerfanta]);
    }

    /**
     * Creates a new IsiltasunAdministratiboa entity.
     */
    #[IsGranted('ROLE_ADMIN')]    
    #[Route(path: '/new', name: 'isiltasunadministratiboa_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $isiltasunAdministratiboa = new IsiltasunAdministratiboa();
        $form = $this->createForm(IsiltasunAdministratiboaType::class, $isiltasunAdministratiboa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $isiltasunAdministratiboa = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $isiltasunAdministratiboa->setUdala($user->getUdala());
            $this->em->persist($isiltasunAdministratiboa);
            $this->em->flush();

            return $this->redirectToRoute('isiltasunadministratiboa_index');
        }

        return $this->render('isiltasunadministratiboa/new.html.twig', ['isiltasunAdministratiboa' => $isiltasunAdministratiboa, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a IsiltasunAdministratiboa entity.
     */
    #[Route(path: '/{id}', name: 'isiltasunadministratiboa_show', methods: ['GET'])]
    public function show(IsiltasunAdministratiboa $isiltasunAdministratiboa): Response
    {
        $deleteForm = $this->createDeleteForm($isiltasunAdministratiboa);

        return $this->render('isiltasunadministratiboa/show.html.twig', ['isiltasunAdministratiboa' => $isiltasunAdministratiboa, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing IsiltasunAdministratiboa entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]    
    #[Route(path: '/{id}/edit', name: 'isiltasunadministratiboa_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, IsiltasunAdministratiboa $isiltasunAdministratiboa)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($isiltasunAdministratiboa->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($isiltasunAdministratiboa);
            $editForm = $this->createForm(IsiltasunAdministratiboaType::class, $isiltasunAdministratiboa);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($isiltasunAdministratiboa);
                $this->em->flush();
    
                return $this->redirectToRoute('isiltasunadministratiboa_edit', ['id' => $isiltasunAdministratiboa->getId()]);
            }
    
            return $this->render('isiltasunadministratiboa/edit.html.twig', ['isiltasunAdministratiboa' => $isiltasunAdministratiboa, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');
        }            
    }

    /**
     * Deletes a IsiltasunAdministratiboa entity.
     */
    #[Route(path: '/{id}', name: 'isiltasunadministratiboa_delete', methods: ['DELETE'])]
    public function delete(Request $request, IsiltasunAdministratiboa $isiltasunAdministratiboa): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($isiltasunAdministratiboa->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($isiltasunAdministratiboa);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($isiltasunAdministratiboa);
                $this->em->flush();
            }
            return $this->redirectToRoute('isiltasunadministratiboa_index');
        }else
        {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Creates a form to delete a IsiltasunAdministratiboa entity.
     *
     * @param IsiltasunAdministratiboa $isiltasunAdministratiboa The IsiltasunAdministratiboa entity
     *
     * @return Form The form
     */
    private function createDeleteForm(IsiltasunAdministratiboa $isiltasunAdministratiboa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('isiltasunadministratiboa_delete', ['id' => $isiltasunAdministratiboa->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
