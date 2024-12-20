<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Eremuak;
use App\Form\EremuakType;
use App\Repository\EremuakRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * Eremuak controller.
 *
 * @Route("/{_locale}/eremuak")
 */
class EremuakController extends AbstractController
{
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, EremuakRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Eremuak entities.
     *
     * @Route("/", defaults={"page" = 1}, name="eremuak_index")
     * @Route("/page{page}", name="eremuak_index_paginated")
     * @Method("GET")
     */
    public function indexAction($page)
    {

        if ($this->isGranted('ROLE_SUPER_ADMIN'))
        {
            $eremuaks = $this->repo->findAll();

            $adapter = new ArrayAdapter($eremuaks);
            $pagerfanta = new Pagerfanta($adapter);

            $deleteForms = array();
            foreach ($eremuaks as $eremuak) {
                $deleteForms[$eremuak->getId()] = $this->createDeleteForm($eremuak)->createView();
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
            } catch (\Pagerfanta\Exception\NotValidCurrentPageException $e) {
                throw $this->createNotFoundException("Orria ez da existitzen");
            }

            return $this->render('eremuak/index.html.twig', array(
                'eremuaks' => $entities,
                'deleteforms' => $deleteForms,
                'pager' => $pagerfanta,
            ));
        }else if ($this->isGranted('ROLE_ADMIN'))
        {

            $udala=$this->getUser()->getUdala()->getId();
            $query = $this->em->createQuery('
              SELECT f.id
                FROM App:Eremuak f
                WHERE f.udala = :udala
              ');
            $query->setParameter('udala', $udala);
            $eremuid = $query->getSingleResult();

            $eremuak=$this->getUser()->getUdala()->getEremuak();

            return $this->redirectToRoute('eremuak_edit', array('id' => $eremuid['id']));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Eremuak entity.
     *
     * @Route("/new", name="eremuak_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        if ($this->isGranted('ROLE_SUPER_ADMIN'))
        {
            $eremuak = new Eremuak();
            $form = $this->createForm(EremuakType::class, $eremuak);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($eremuak);
                $this->em->flush();

//                return $this->redirectToRoute('eremuak_show', array('id' => $eremuak->getId()));
                return $this->redirectToRoute('eremuak_index');
            }

            return $this->render('eremuak/new.html.twig', array(
                'eremuak' => $eremuak,
                'form' => $form->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Eremuak entity.
     *
     * @Route("/{id}", name="eremuak_show")
     * @Method("GET")
     */
    public function showAction(Eremuak $eremuak)
    {
        $deleteForm = $this->createDeleteForm($eremuak);

        return $this->render('eremuak/show.html.twig', array(
            'eremuak' => $eremuak,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Eremuak entity.
     *
     * @Route("/{id}/edit", name="eremuak_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Eremuak $eremuak)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($eremuak->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($eremuak);
            $editForm = $this->createForm(EremuakType::class, $eremuak);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($eremuak);
                $this->em->flush();

                return $this->redirectToRoute('eremuak_edit', array('id' => $eremuak->getId()));
            }

            return $this->render('eremuak/edit.html.twig', array(
                'eremuak' => $eremuak,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Eremuak entity.
     *
     * @Route("/{id}", name="eremuak_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Eremuak $eremuak)
    {

        if($this->isGranted('ROLE_SUPER_ADMIN'))
        {
            $form = $this->createDeleteForm($eremuak);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($eremuak);
                $this->em->flush();
            }
            return $this->redirectToRoute('eremuak_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a form to delete a Eremuak entity.
     *
     * @param Eremuak $eremuak The Eremuak entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Eremuak $eremuak)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('eremuak_delete', array('id' => $eremuak->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
