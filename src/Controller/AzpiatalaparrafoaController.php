<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Azpiatalaparrafoa;
use App\Form\AzpiatalaparrafoaType;
use App\Repository\AzpiatalaparrafoaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Azpiatalaparrafoa controller.
 *
 * @Route("/{_locale}/azpiatalaparrafoa")
 */
class AzpiatalaparrafoaController extends AbstractController
{

    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, AzpiatalaparrafoaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Azpiatalaparrafoa entities.
     *
     * @Route("/", name="azpiatalaparrafoa_index")
     * @Method("GET")
     */
    public function index(): Response
    {
        $azpiatalaparrafoas = $this->repo->findAll();

        $deleteForms = [];
        foreach ($azpiatalaparrafoas as $azpiatalaparrafoa) {
            $deleteForms[$azpiatalaparrafoa->getId()] = $this->createDeleteForm($azpiatalaparrafoa)->createView();
        }

        return $this->render('azpiatalaparrafoa/index.html.twig', ['azpiatalaparrafoas' => $azpiatalaparrafoas, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Azpiatalaparrafoa entity.
     *
     * @Route("/new", name="azpiatalaparrafoa_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        if ($this->isGranted('ROLE_ADMIN')) 
        {
            $azpiatalaparrafoa = new Azpiatalaparrafoa();
            $form = $this->createForm(AzpiatalaparrafoaType::class, $azpiatalaparrafoa);
            $form->handleRequest($request);
    
//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());
            
            if ($form->isSubmitted() && $form->isValid()) {
//                $azpiatalaparrafoa->setCreatedAt(new \DateTime());
//                $azpiatalaparrafoa->setUpdatedAt(new \DateTime());
                $this->em->persist($azpiatalaparrafoa);
                $this->em->flush();
    
                return $this->redirectToRoute('azpiatalaparrafoa_show', ['id' => $azpiatalaparrafoa->getId()]);
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }
    
            return $this->render('azpiatalaparrafoa/new.html.twig', ['azpiatalaparrafoa' => $azpiatalaparrafoa, 'form' => $form->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Finds and displays a Azpiatalaparrafoa entity.
     *
     * @Route("/{id}", name="azpiatalaparrafoa_show")
     * @Method("GET")
     */
    public function show(Azpiatalaparrafoa $azpiatalaparrafoa): Response
    {
        $deleteForm = $this->createDeleteForm($azpiatalaparrafoa);

        return $this->render('azpiatalaparrafoa/show.html.twig', ['azpiatalaparrafoa' => $azpiatalaparrafoa, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Azpiatalaparrafoa entity.
     *
     * @Route("/{id}/edit", name="azpiatalaparrafoa_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Azpiatalaparrafoa $azpiatalaparrafoa)
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($azpiatalaparrafoa->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($azpiatalaparrafoa);
            $editForm = $this->createForm(AzpiatalaparrafoaType::class, $azpiatalaparrafoa);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($azpiatalaparrafoa);
                $this->em->flush();

                return $this->redirectToRoute('azpiatalaparrafoa_edit', ['id' => $azpiatalaparrafoa->getId()]);
            }

            return $this->render('azpiatalaparrafoa/edit.html.twig', ['azpiatalaparrafoa' => $azpiatalaparrafoa, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Azpiatalaparrafoa entity.
     *
     * @Route("/{id}", name="azpiatalaparrafoa_delete")
     * @Method("DELETE")
     */
    public function delete(Request $request, Azpiatalaparrafoa $azpiatalaparrafoa): RedirectResponse
    {
        if((($this->isGranted('ROLE_ADMIN')) && ($azpiatalaparrafoa->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($azpiatalaparrafoa);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($azpiatalaparrafoa);
                $this->em->flush();
            }
            return $this->redirectToRoute('azpiatalaparrafoa_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a form to delete a Azpiatalaparrafoa entity.
     *
     * @param Azpiatalaparrafoa $azpiatalaparrafoa The Azpiatalaparrafoa entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Azpiatalaparrafoa $azpiatalaparrafoa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('azpiatalaparrafoa_delete', ['id' => $azpiatalaparrafoa->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
