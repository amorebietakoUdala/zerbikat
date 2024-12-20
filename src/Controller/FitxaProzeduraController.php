<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\FitxaProzedura;
use App\Form\FitxaProzeduraType;
use App\Repository\FitxaProzeduraRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * FitxaProzedura controller.
 *
 * @Route("/{_locale}/fitxaprozedura")
 */
class FitxaProzeduraController extends AbstractController
{
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em, FitxaProzeduraRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * Lists all FitxaProzedura entities.
     *
     * @Route("/", name="fitxaprozedura_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $fitxaProzeduras = $this->repo->findAll();

        return $this->render('fitxaprozedura/index.html.twig', array(
            'fitxaProzeduras' => $fitxaProzeduras,
        ));
    }

    /**
     * Creates a new FitxaProzedura entity.
     *
     * @Route("/new", name="fitxaprozedura_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $fitxaProzedura = new FitxaProzedura();
        $form = $this->createForm(FitxaProzeduraType::class, $fitxaProzedura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($fitxaProzedura);
            $this->em->flush();

            return $this->redirectToRoute('fitxaprozedura_show', array('id' => $fitxaProzedura->getId()));
        } else
        {
            $form->getData()->setUdala($this->getUser()->getUdala());
            $form->setData($form->getData());
        }

        return $this->render('fitxaprozedura/new.html.twig', array(
            'fitxaProzedura' => $fitxaProzedura,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a FitxaProzedura entity.
     *
     * @Route("/{id}", name="fitxaprozedura_show")
     * @Method("GET")
     */
    public function showAction(FitxaProzedura $fitxaProzedura)
    {
        $deleteForm = $this->createDeleteForm($fitxaProzedura);

        return $this->render('fitxaprozedura/show.html.twig', array(
            'fitxaProzedura' => $fitxaProzedura,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing FitxaProzedura entity.
     *
     * @Route("/{id}/edit", name="fitxaprozedura_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, FitxaProzedura $fitxaProzedura)
    {
        $deleteForm = $this->createDeleteForm($fitxaProzedura);
        $editForm = $this->createForm(FitxaProzeduraType::class, $fitxaProzedura);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em->persist($fitxaProzedura);
            $this->em->flush();

            return $this->redirectToRoute('fitxaprozedura_edit', array('id' => $fitxaProzedura->getId()));
        }

        return $this->render('fitxaprozedura/edit.html.twig', array(
            'fitxaProzedura' => $fitxaProzedura,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));

    }

    /**
     * Deletes a FitxaProzedura entity.
     *
     * @Route("/{id}", name="fitxaprozedura_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, FitxaProzedura $fitxaProzedura)
    {
        $form = $this->createDeleteForm($fitxaProzedura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->remove($fitxaProzedura);
            $this->em->flush();
        }

        return $this->redirectToRoute('fitxaprozedura_index');
    }

    /**
     * Creates a form to delete a FitxaProzedura entity.
     *
     * @param FitxaProzedura $fitxaProzedura The FitxaProzedura entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(FitxaProzedura $fitxaProzedura)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fitxaprozedura_delete', array('id' => $fitxaProzedura->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
