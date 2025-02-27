<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Datuenbabesa;
use App\Form\DatuenbabesaType;
use App\Repository\DatuenbabesaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * Datuenbabesa controller.
 */
#[Route(path: '/{_locale}/datuenbabesa')]
class DatuenbabesaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private DatuenbabesaRepository $repo
    )
    {
    }

    /**
     * Lists all Datuenbabesa entities.
     */
    #[IsGranted("ROLE_KUDEAKETA")]
    #[Route(path: '/', defaults: ['page' => 1], name: 'datuenbabesa_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'datuenbabesa_index_paginated', methods: ['GET'])]
    public function index($page)
    {
        $datuenbabesas = $this->repo->findBy( [], ['kodea'=>'ASC'] );

        $deleteForms = [];
        foreach ($datuenbabesas as $datuenbabesa) {
            $deleteForms[$datuenbabesa->getId()] = $this->createDeleteForm($datuenbabesa)->createView();
        }

        return $this->render('datuenbabesa/index.html.twig', ['datuenbabesas' => $datuenbabesas, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Datuenbabesa entity.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/new', name: 'datuenbabesa_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {

        $datuenbabesa = new Datuenbabesa();
        $form = $this->createForm(DatuenbabesaType::class, $datuenbabesa);
        $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $datuenbabesa = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $datuenbabesa->setUdala($udala);
            $this->em->persist($datuenbabesa);
            $this->em->flush();

//                return $this->redirectToRoute('datuenbabesa_show', array('id' => $datuenbabesa->getId()));
            return $this->redirectToRoute('datuenbabesa_index');
        }

        return $this->render('datuenbabesa/new.html.twig', ['datuenbabesa' => $datuenbabesa, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Datuenbabesa entity.
     */
    #[Route(path: '/{id}', name: 'datuenbabesa_show', methods: ['GET'])]
    public function show(Datuenbabesa $datuenbabesa): Response
    {
        $deleteForm = $this->createDeleteForm($datuenbabesa);

        return $this->render('datuenbabesa/show.html.twig', ['datuenbabesa' => $datuenbabesa, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Datuenbabesa entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'datuenbabesa_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Datuenbabesa $datuenbabesa)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($datuenbabesa->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($datuenbabesa);
            $editForm = $this->createForm(DatuenbabesaType::class, $datuenbabesa);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($datuenbabesa);
                $this->em->flush();
    
                return $this->redirectToRoute('datuenbabesa_edit', ['id' => $datuenbabesa->getId()]);
            }
    
            return $this->render('datuenbabesa/edit.html.twig', ['datuenbabesa' => $datuenbabesa, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');
        }            
    }

    /**
     * Deletes a Datuenbabesa entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'datuenbabesa_delete', methods: ['DELETE'])]
    public function delete(Request $request, Datuenbabesa $datuenbabesa): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($datuenbabesa->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($datuenbabesa);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($datuenbabesa);
                $this->em->flush();
            }
            return $this->redirectToRoute('datuenbabesa_index');
        }else
        {
            //baimenik ez
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Creates a form to delete a Datuenbabesa entity.
     *
     * @param Datuenbabesa $datuenbabesa The Datuenbabesa entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Datuenbabesa $datuenbabesa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('datuenbabesa_delete', ['id' => $datuenbabesa->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
