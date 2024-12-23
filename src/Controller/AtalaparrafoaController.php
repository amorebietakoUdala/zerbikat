<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Atalaparrafoa;
use App\Form\AtalaparrafoaType;
use App\Repository\AtalaparrafoaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Atalaparrafoa controller.
 *
 * @Route("/{_locale}/atalaparrafoa")
 */
class AtalaparrafoaController extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, AtalaparrafoaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Atalaparrafoa entities.
     *
     * @Route("/", name="atalaparrafoa_index", methods={"GET"})
     */
    public function index()
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $atalaparrafoas = $this->repo->findAll();

            $deleteForms = [];
            foreach ($atalaparrafoas as $parrafoa) {
                $deleteForms[$parrafoa->getId()] = $this->createDeleteForm($parrafoa)->createView();
            }

            return $this->render('atalaparrafoa/index.html.twig', ['atalaparrafoas' => $atalaparrafoas, 'deleteforms' => $deleteForms]);
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Atalaparrafoa entity.
     *
     * @Route("/new", name="atalaparrafoa_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $atalaparrafoa = new Atalaparrafoa();
            $form = $this->createForm(AtalaParrafoaType::class, $atalaparrafoa);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($atalaparrafoa);
                $this->em->flush();

                return $this->redirectToRoute('atalaparrafoa_show', ['id' => $atalaparrafoa->getId()]);
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('atalaparrafoa/new.html.twig', ['atalaparrafoa' => $atalaparrafoa, 'form' => $form->createView()]);
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Atalaparrafoa entity.
     *
     * @Route("/{id}", name="atalaparrafoa_show", methods={"GET"})
     */
    public function show(Atalaparrafoa $atalaparrafoa): Response
    {
        $deleteForm = $this->createDeleteForm($atalaparrafoa);

        return $this->render('atalaparrafoa/show.html.twig', ['atalaparrafoa' => $atalaparrafoa, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Atalaparrafoa entity.
     *
     * @Route("/{id}/edit", name="atalaparrafoa_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Atalaparrafoa $atalaparrafoa)
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($atalaparrafoa->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($atalaparrafoa);
            $editForm = $this->createForm(AtalaParrafoaType::class, $atalaparrafoa);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($atalaparrafoa);
                $this->em->flush();

                return $this->redirectToRoute('atalaparrafoa_edit', ['id' => $atalaparrafoa->getId()]);
            }

            return $this->render('atalaparrafoa/edit.html.twig', ['atalaparrafoa' => $atalaparrafoa, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Atalaparrafoa entity.
     *
     * @Route("/{id}", name="atalaparrafoa_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Atalaparrafoa $atalaparrafoa): RedirectResponse
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($atalaparrafoa->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {        
            $form = $this->createDeleteForm($atalaparrafoa);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($atalaparrafoa);
                $this->em->flush();
            }
            return $this->redirectToRoute('atalaparrafoa_index');
        }else
        {
            //baimenik ez
//            return $this->redirectToRoute('fitxa_index');
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a form to delete a Atalaparrafoa entity.
     *
     * @param Atalaparrafoa $atalaparrafoa The Atalaparrafoa entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Atalaparrafoa $atalaparrafoa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('atalaparrafoa_delete', ['id' => $atalaparrafoa->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
