<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Kontzeptua;
use App\Form\KontzeptuaType;
use App\Repository\KontzeptuaRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Kontzeptua controller.
 *
 * @Route("/{_locale}/kontzeptua")
 */
class KontzeptuaController extends AbstractController
{
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, KontzeptuaRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all Kontzeptua entities.
     *
     * @Route("/", name="kontzeptua_index")
     * @Method("GET")
     */
    public function indexAction()
    {

        if ($this->isGranted('ROLE_KUDEAKETA')) {
            $kontzeptuas = $this->repo->findAll();

            $deleteForms = array();
            foreach ($kontzeptuas as $kontzeptua) {
                $deleteForms[$kontzeptua->getId()] = $this->createDeleteForm($kontzeptua)->createView();
            }

            return $this->render('kontzeptua/index.html.twig', array(
                'kontzeptuas' => $kontzeptuas,
                'deleteforms' => $deleteForms
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Creates a new Kontzeptua entity.
     *
     * @Route("/new", name="kontzeptua_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        if ($this->isGranted('ROLE_ADMIN'))
        {
            $kontzeptua = new Kontzeptua();
            $form = $this->createForm(KontzeptuaType::class, $kontzeptua);
            $form->handleRequest($request);

//            $form->getData()->setUdala($this->getUser()->getUdala());
//            $form->setData($form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
//                $kontzeptua->setCreatedAt(new \DateTime());
//                $kontzeptua->setUpdatedAt(new \DateTime());
                $this->em->persist($kontzeptua);
                $this->em->flush();

//                return $this->redirectToRoute('kontzeptua_show', array('id' => $kontzeptua->getId()));
                return $this->redirectToRoute('kontzeptua_index');
            } else
            {
                $form->getData()->setUdala($this->getUser()->getUdala());
                $form->setData($form->getData());
            }

            return $this->render('kontzeptua/new.html.twig', array(
                'kontzeptua' => $kontzeptua,
                'form' => $form->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Kontzeptua entity.
     *
     * @Route("/{id}", name="kontzeptua_show")
     * @Method("GET")
     */
    public function showAction(Kontzeptua $kontzeptua)
    {
        $deleteForm = $this->createDeleteForm($kontzeptua);

        return $this->render('kontzeptua/show.html.twig', array(
            'kontzeptua' => $kontzeptua,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Kontzeptua entity.
     *
     * @Route("/{id}/edit", name="kontzeptua_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Kontzeptua $kontzeptua)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($kontzeptua->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($kontzeptua);
            $editForm = $this->createForm(KontzeptuaType::class, $kontzeptua);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($kontzeptua);
                $this->em->flush();

                return $this->redirectToRoute('kontzeptua_edit', array('id' => $kontzeptua->getId()));
            }

            return $this->render('kontzeptua/edit.html.twig', array(
                'kontzeptua' => $kontzeptua,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Deletes a Kontzeptua entity.
     *
     * @Route("/{id}", name="kontzeptua_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Kontzeptua $kontzeptua)
    {

        if((($this->isGranted('ROLE_ADMIN')) && ($kontzeptua->getUdala()==$this->getUser()->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $form = $this->createDeleteForm($kontzeptua);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($kontzeptua);
                $this->em->flush();
            }
            return $this->redirectToRoute('kontzeptua_index');
        }else
        {
            //baimenik ez
            return $this->redirectToRoute('backend_errorea');
        }            
    }

    /**
     * Creates a form to delete a Kontzeptua entity.
     *
     * @param Kontzeptua $kontzeptua The Kontzeptua entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Kontzeptua $kontzeptua)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('kontzeptua_delete', array('id' => $kontzeptua->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
