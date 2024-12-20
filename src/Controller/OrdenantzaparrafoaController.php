<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ordenantzaparrafoa;
use App\Form\OrdenantzaparrafoaType;
use App\Repository\OrdenantzaparrafoaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ordenantzaparrafoa controller.
 *
 * @Route("/{_locale}/ordenantzaparrafoa")
 */
class OrdenantzaparrafoaController extends AbstractController
{
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, OrdenantzaparrafoaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Ordenantzaparrafoa entities.
     *
     * @Route("/", name="ordenantzaparrafoa_index")
     * @Method("GET")
     */
    public function index()
    {

        if ($this->isGranted('ROLE_ADMIN')) {
            $ordenantzaparrafoas = $this->repo->findAll();

            $deleteForms = [];
            foreach ($ordenantzaparrafoas as $ordenantzaparrafoa) {
                $deleteForms[$ordenantzaparrafoa->getId()] = $this->createDeleteForm($ordenantzaparrafoa)->createView();
            }

            return $this->render('ordenantzaparrafoa/index.html.twig', ['ordenantzaparrafoas' => $ordenantzaparrafoas, 'deleteforms' => $deleteForms]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Ordenantzaparrafoa entity.
     *
     * @Route("/new", name="ordenantzaparrafoa_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN'))
        {
            $ordenantzaparrafoa = new Ordenantzaparrafoa();
            $form = $this->createForm(OrdenantzaparrafoaType::class, $ordenantzaparrafoa);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $ordenantzaparrafoa->setCreatedAt(new \DateTime());
                $ordenantzaparrafoa->setUpdatedAt(new \DateTime());
                $this->em->persist($ordenantzaparrafoa);
                $this->em->flush();

//                return $this->redirectToRoute('ordenantzaparrafoa_show', array('id' => $ordenantzaparrafoa->getId()));
                return $this->redirectToRoute('ordenantzaparrafoa_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('ordenantzaparrafoa/new.html.twig', ['ordenantzaparrafoa' => $ordenantzaparrafoa, 'form' => $form->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Ordenantzaparrafoa entity.
     *
     * @Route("/{id}", name="ordenantzaparrafoa_show")
     * @Method("GET")
     */
    public function show(Ordenantzaparrafoa $ordenantzaparrafoa): Response
    {
        $deleteForm = $this->createDeleteForm($ordenantzaparrafoa);

        return $this->render('ordenantzaparrafoa/show.html.twig', ['ordenantzaparrafoa' => $ordenantzaparrafoa, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Ordenantzaparrafoa entity.
     *
     * @Route("/{id}/edit", name="ordenantzaparrafoa_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Ordenantzaparrafoa $ordenantzaparrafoa)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($ordenantzaparrafoa->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($ordenantzaparrafoa);
            $editForm = $this->createForm(OrdenantzaparrafoaType::class, $ordenantzaparrafoa);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($ordenantzaparrafoa);
                $this->em->flush();

                return $this->redirectToRoute('ordenantzaparrafoa_edit', ['id' => $ordenantzaparrafoa->getId()]);
            }

            return $this->render('ordenantzaparrafoa/edit.html.twig', ['ordenantzaparrafoa' => $ordenantzaparrafoa, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Ordenantzaparrafoa entity.
     *
     * @Route("/{id}", name="ordenantzaparrafoa_delete")
     * @Method("DELETE")
     */
    public function delete(Request $request, Ordenantzaparrafoa $ordenantzaparrafoa): RedirectResponse
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($ordenantzaparrafoa->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($ordenantzaparrafoa);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($ordenantzaparrafoa);
                $this->em->flush();
            }
            return $this->redirectToRoute('ordenantzaparrafoa_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Creates a form to delete a Ordenantzaparrafoa entity.
     *
     * @param Ordenantzaparrafoa $ordenantzaparrafoa The Ordenantzaparrafoa entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ordenantzaparrafoa $ordenantzaparrafoa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ordenantzaparrafoa_delete', ['id' => $ordenantzaparrafoa->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
