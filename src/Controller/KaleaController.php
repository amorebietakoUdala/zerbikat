<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Kalea;
use App\Form\KaleaType;
use App\Repository\KaleaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Kalea controller.
 *
 * @Route("/{_locale}/kalea")
 */
class KaleaController extends AbstractController
{
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, KaleaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Kalea entities.
     *
     * @Route("/", name="kalea_index", methods={"GET"})
     */
    public function index()
    {

        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $kaleas = $this->repo->findAll();

            $deleteForms = [];
            foreach ($kaleas as $kalea) {
                $deleteForms[$kalea->getId()] = $this->createDeleteForm($kalea)->createView();
            }


            return $this->render('kalea/index.html.twig', ['kaleas' => $kaleas, 'deleteforms' => $deleteForms]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Kalea entity.
     *
     * @Route("/new", name="kalea_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN')) 
        {
            $kalea = new Kalea();
            $form = $this->createForm(KaleaType::class, $kalea);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());
            
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($kalea);
                $this->em->flush();
    
//                return $this->redirectToRoute('kalea_show', array('id' => $kalea->getId()));
                return $this->redirectToRoute('kalea_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }
    
            return $this->render('kalea/new.html.twig', ['kalea' => $kalea, 'form' => $form->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Finds and displays a Kalea entity.
     *
     * @Route("/{id}", name="kalea_show", methods={"GET"})
     */
    public function show(Kalea $kalea): Response
    {
        $deleteForm = $this->createDeleteForm($kalea);

        return $this->render('kalea/show.html.twig', ['kalea' => $kalea, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Kalea entity.
     *
     * @Route("/{id}/edit", name="kalea_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Kalea $kalea)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($kalea->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($kalea);
            $editForm = $this->createForm(KaleaType::class, $kalea);
            $editForm->handleRequest($request);
    
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($kalea);
                $this->em->flush();
    
                return $this->redirectToRoute('kalea_edit', ['id' => $kalea->getId()]);
            }
    
            return $this->render('kalea/edit.html.twig', ['kalea' => $kalea, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Deletes a Kalea entity.
     *
     * @Route("/{id}", name="kalea_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Kalea $kalea): RedirectResponse
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($kalea->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($kalea);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($kalea);
                $this->em->flush();
            }
            return $this->redirectToRoute('kalea_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Creates a form to delete a Kalea entity.
     *
     * @param Kalea $kalea The Kalea entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Kalea $kalea)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('kalea_delete', ['id' => $kalea->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
