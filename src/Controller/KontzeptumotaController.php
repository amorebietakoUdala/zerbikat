<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Kontzeptumota;
use App\Form\KontzeptumotaType;
use App\Repository\KontzeptumotaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * Kontzeptumota controller.
 *
 * @Route("/{_locale}/kontzeptumota")
 */
class KontzeptumotaController extends AbstractController
{
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, KontzeptumotaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Kontzeptumota entities.
     *
     * @Route("/", defaults={"page" = 1}, name="kontzeptumota_index")
     * @Route("/page{page}", name="kontzeptumota_index_paginated")
     * @Method("GET")
     */
    public function index($page)
    {

        if ($this->isGranted('ROLE_KUDEAKETA'))
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
            } catch (\Pagerfanta\Exception\NotValidCurrentPageException $e) {
                throw $this->createNotFoundException("Orria ez da existitzen");
            }

            return $this->render('kontzeptumota/index.html.twig', [
                //                'kontzeptumotas' => $kontzeptumotas,
                'kontzeptumotas' => $entities,
                'deleteforms' => $deleteForms,
                'pager' => $pagerfanta,
            ]);

        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Kontzeptumota entity.
     *
     * @Route("/new", name="kontzeptumota_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN'))
        {
            $kontzeptumotum = new Kontzeptumota();
            $form = $this->createForm(KontzeptumotaType::class, $kontzeptumotum);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($kontzeptumotum);
                $this->em->flush();

//                return $this->redirectToRoute('kontzeptumota_show', array('id' => $kontzeptumotum->getId()));
                return $this->redirectToRoute('kontzeptumota_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('kontzeptumota/new.html.twig', ['kontzeptumotum' => $kontzeptumotum, 'form' => $form->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Kontzeptumota entity.
     *
     * @Route("/{id}", name="kontzeptumota_show")
     * @Method("GET")
     */
    public function show(Kontzeptumota $kontzeptumotum): \Symfony\Component\HttpFoundation\Response
    {
        $deleteForm = $this->createDeleteForm($kontzeptumotum);

        return $this->render('kontzeptumota/show.html.twig', ['kontzeptumotum' => $kontzeptumotum, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Kontzeptumota entity.
     *
     * @Route("/{id}/edit", name="kontzeptumota_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Kontzeptumota $kontzeptumotum)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($kontzeptumotum->getUdala()==$this->getUser()->getUdala()))
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
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Kontzeptumota entity.
     *
     * @Route("/{id}", name="kontzeptumota_delete")
     * @Method("DELETE")
     */
    public function delete(Request $request, Kontzeptumota $kontzeptumotum): \Symfony\Component\HttpFoundation\RedirectResponse
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($kontzeptumotum->getUdala()==$this->getUser()->getUdala()))
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
            return $this->redirectToRoute('backend_errorea');
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
