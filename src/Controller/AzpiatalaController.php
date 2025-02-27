<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Azpiatala;
use App\Form\AzpiatalaType;
use App\Repository\AzpiatalaRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Azpiatala controller.
 */
#[Route(path: '/{_locale}/azpiatala')]
class AzpiatalaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private AzpiatalaRepository $repo
    )
    {
    }

    /**
     * Lists all Azpiatala entities.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/', defaults: ['page' => 1], name: 'azpiatala_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'azpiatala_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $azpiatalas = $this->repo->findBy( [], ['kodea'=>'ASC'] );

        $adapter = new ArrayAdapter($azpiatalas);
        $pagerfanta = new Pagerfanta($adapter);

        $deleteForms = [];
        foreach ($azpiatalas as $azpiatala) {
            $deleteForms[$azpiatala->getId()] = $this->createDeleteForm($azpiatala)->createView();
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

        return $this->render('azpiatala/index.html.twig', [
            //                'azpiatalas' => $azpiatalas,
            'azpiatalas' => $entities,
            'deleteforms' => $deleteForms,
            'pager' => $pagerfanta,
        ]);
    }

    /**
     * Creates a new Azpiatala entity.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/new', name: 'azpiatala_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $azpiatala = new Azpiatala();
        $form = $this->createForm(AzpiatalaType::class, $azpiatala);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO gehitu eremu hauek doctrineExtensions horiekin?
//                $azpiatala->setCreatedAt(new \DateTime());
//                $azpiatala->setUpdatedAt(new \DateTime());
            $azpiatala = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $azpiatala->setUdala($udala);                
            $this->em->persist($azpiatala);
            $this->em->flush();

            return $this->redirectToRoute('azpiatala_show', ['id' => $azpiatala->getId()]);
        }

        return $this->render('azpiatala/new.html.twig', ['azpiatala' => $azpiatala, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Azpiatala entity.
     */
    #[Route(path: '/{id}', name: 'azpiatala_show', methods: ['GET'])]
    public function show(Azpiatala $azpiatala): Response
    {
        $deleteForm = $this->createDeleteForm($azpiatala);

        return $this->render('azpiatala/show.html.twig', ['azpiatala' => $azpiatala, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Azpiatala entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'azpiatala_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Azpiatala $azpiatala)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($azpiatala->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            // Create an ArrayCollection of the current Kontzeptuak objects in the database
            $originalKontzeptuak = new ArrayCollection();
            foreach ($azpiatala->getKontzeptuak() as $kontzeptu) {
                $originalKontzeptuak->add($kontzeptu);
            }
            // Create an ArrayCollection of the current Kontzeptuak objects in the database
            $originalParrafoak = new ArrayCollection();
            foreach ($azpiatala->getParrafoak() as $parrafo) {
                $originalParrafoak->add($parrafo);
            }

            $deleteForm = $this->createDeleteForm($azpiatala);
            $editForm = $this->createForm(AzpiatalaType::class, $azpiatala);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                foreach ($originalKontzeptuak as $kontzeptu)
                {
                    if (false === $azpiatala->getKontzeptuak()->contains($kontzeptu))
                    {
                        $kontzeptu->setAzpiatala(null);
                        $this->em->remove($kontzeptu);
                        $this->em->persist($azpiatala);
                    }
                }
                foreach ($originalParrafoak as $parrafo)
                {
                    if (false === $azpiatala->getParrafoak()->contains($parrafo))
                    {
                        $parrafo->setAzpiatala(null);
                        $this->em->remove($parrafo);

                        $this->em->persist($azpiatala);
                    }
                }

                $this->em->persist($azpiatala);
                $this->em->flush();

                return $this->redirectToRoute('azpiatala_edit', ['id' => $azpiatala->getId()]);
            }

            return $this->render('azpiatala/edit.html.twig', ['azpiatala' => $azpiatala, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            throw new AccessDeniedHttpException('Access Denied');

        }
    }

    /**
     * Deletes a Azpiatala entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'azpiatala_delete', methods: ['DELETE'])]
    public function delete(Request $request, Azpiatala $azpiatala): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($azpiatala->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($azpiatala);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($azpiatala);
                $this->em->flush();
            }
            return $this->redirectToRoute('azpiatala_index');
        }else
        {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }            
    }

    /**
     * Creates a form to delete a Azpiatala entity.
     *
     * @param Azpiatala $azpiatala The Azpiatala entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Azpiatala $azpiatala)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('azpiatala_delete', ['id' => $azpiatala->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
