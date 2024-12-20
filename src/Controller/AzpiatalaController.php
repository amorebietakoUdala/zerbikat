<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Azpiatala;
use App\Form\AzpiatalaType;
use App\Repository\AzpiatalaRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Azpiatala controller.
 *
 * @Route("/{_locale}/azpiatala")
 */
class AzpiatalaController extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, AzpiatalaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Azpiatala entities.
     *
     * @Route("/", defaults={"page" = 1}, name="azpiatala_index")
     * @Route("/page{page}", name="azpiatala_index_paginated")
     * @Method("GET")
     */
    public function index($page)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
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
            } catch (\Pagerfanta\Exception\NotValidCurrentPageException $e) {
                throw $this->createNotFoundException("Orria ez da existitzen");
            }




            return $this->render('azpiatala/index.html.twig', [
                //                'azpiatalas' => $azpiatalas,
                'azpiatalas' => $entities,
                'deleteforms' => $deleteForms,
                'pager' => $pagerfanta,
            ]);
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Azpiatala entity.
     *
     * @Route("/new", name="azpiatala_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        if ($this->isGranted('ROLE_ADMIN'))
        {
            $azpiatala = new Azpiatala();
            $form = $this->createForm(AzpiatalaType::class, $azpiatala);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
//                $azpiatala->setCreatedAt(new \DateTime());
//                $azpiatala->setUpdatedAt(new \DateTime());
                $this->em->persist($azpiatala);
                $this->em->flush();

                return $this->redirectToRoute('azpiatala_show', ['id' => $azpiatala->getId()]);
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('azpiatala/new.html.twig', ['azpiatala' => $azpiatala, 'form' => $form->createView()]);
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Azpiatala entity.
     *
     * @Route("/{id}", name="azpiatala_show")
     * @Method("GET")
     */
    public function show(Azpiatala $azpiatala): Response
    {
        $deleteForm = $this->createDeleteForm($azpiatala);

        return $this->render('azpiatala/show.html.twig', ['azpiatala' => $azpiatala, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Azpiatala entity.
     *
     * @Route("/{id}/edit", name="azpiatala_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Azpiatala $azpiatala)
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($azpiatala->getUdala()==$this->getUser()->getUdala()))
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
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Azpiatala entity.
     *
     * @Route("/{id}", name="azpiatala_delete")
     * @Method("DELETE")
     */
    public function delete(Request $request, Azpiatala $azpiatala): RedirectResponse
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($azpiatala->getUdala()==$this->getUser()->getUdala()))
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
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Creates a form to delete a Azpiatala entity.
     *
     * @param Azpiatala $azpiatala The Azpiatala entity
     *
     * @return \Symfony\Component\Form\Form The form
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
