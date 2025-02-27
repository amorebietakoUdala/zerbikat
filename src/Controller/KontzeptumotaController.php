<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Kontzeptumota;
use App\Form\KontzeptumotaType;
use App\Repository\KontzeptumotaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Kontzeptumota controller.
 */
#[Route(path: '/{_locale}/kontzeptumota')]
class KontzeptumotaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private KontzeptumotaRepository $repo
    )
    {
    }

    /**
     * Lists all Kontzeptumota entities.
     */
    #[IsGranted('ROLE_KUDEAKETA')]
    #[Route(path: '/', defaults: ['page' => 1], name: 'kontzeptumota_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'kontzeptumota_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $kontzeptumotas = $this->repo->findAll();

        $adapter = new ArrayAdapter($kontzeptumotas);
        $pagerfanta = new Pagerfanta($adapter);

        $deleteForms = [];
        foreach ($kontzeptumotas as $kontzeptua) {
            $deleteForms[$kontzeptua->getId()] = $this->createDeleteForm($kontzeptua)->createView();
        }

        try {
            $entities = $pagerfanta
                // Le nombre maximum d'éléments par page
//                    ->setMaxPerPage(20)
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

        return $this->render('kontzeptumota/index.html.twig', [
            //                'kontzeptumotas' => $kontzeptumotas,
            'kontzeptumotas' => $entities,
            'deleteforms' => $deleteForms,
            'pager' => $pagerfanta,
        ]);
    }

    /**
     * Creates a new Kontzeptumota entity.
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/new', name: 'kontzeptumota_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $kontzeptumotum = new Kontzeptumota();
        $form = $this->createForm(KontzeptumotaType::class, $kontzeptumotum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $kontzeptumotum = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $kontzeptumotum->setUdala($udala);                
            $this->em->persist($kontzeptumotum);
            $this->em->flush();

            return $this->redirectToRoute('kontzeptumota_index');
        }

        return $this->render('kontzeptumota/new.html.twig', ['kontzeptumotum' => $kontzeptumotum, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Kontzeptumota entity.
     */
    #[Route(path: '/{id}', name: 'kontzeptumota_show', methods: ['GET'])]
    public function show(Kontzeptumota $kontzeptumotum): \Symfony\Component\HttpFoundation\Response
    {
        $deleteForm = $this->createDeleteForm($kontzeptumotum);

        return $this->render('kontzeptumota/show.html.twig', ['kontzeptumotum' => $kontzeptumotum, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Kontzeptumota entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'kontzeptumota_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Kontzeptumota $kontzeptumotum)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($kontzeptumotum->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($kontzeptumotum);
            $editForm = $this->createForm(KontzeptumotaType::class, $kontzeptumotum);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($kontzeptumotum);
                $this->em->flush();

                return $this->redirectToRoute('kontzeptumota_edit', ['id' => $kontzeptumotum->getId()]);
            }

            return $this->render('kontzeptumota/edit.html.twig', ['kontzeptumotum' => $kontzeptumotum, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Kontzeptumota entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'kontzeptumota_delete', methods: ['DELETE'])]
    public function delete(Request $request, Kontzeptumota $kontzeptumotum): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($kontzeptumotum->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($kontzeptumotum);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($kontzeptumotum);
                $this->em->flush();
            }
            return $this->redirectToRoute('kontzeptumota_index');
        }else
        {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Creates a form to delete a Kontzeptumota entity.
     *
     * @param Kontzeptumota $kontzeptumotum The Kontzeptumota entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Kontzeptumota $kontzeptumotum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('kontzeptumota_delete', ['id' => $kontzeptumotum->getId()]))
            ->setMethod(\Symfony\Component\HttpFoundation\Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
