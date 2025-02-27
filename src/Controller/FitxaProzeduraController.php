<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\FitxaProzedura;
use App\Form\FitxaProzeduraType;
use App\Repository\FitxaProzeduraRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * FitxaProzedura controller.
 */
#[Route(path: '/{_locale}/fitxaprozedura')]
class FitxaProzeduraController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em, 
        private FitxaProzeduraRepository $repo
    )
    {
    }

    /**
     * Lists all FitxaProzedura entities.
     */
    #[Route(path: '/', name: 'fitxaprozedura_index', methods: ['GET'])]
    public function index(): Response
    {
        $fitxaProzeduras = $this->repo->findAll();

        return $this->render('fitxaprozedura/index.html.twig', ['fitxaProzeduras' => $fitxaProzeduras]);
    }

    /**
     * Creates a new FitxaProzedura entity.
     */
    #[Route(path: '/new', name: 'fitxaprozedura_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $fitxaProzedura = new FitxaProzedura();
        $form = $this->createForm(FitxaProzeduraType::class, $fitxaProzedura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fitxaProzedura = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $fitxaProzedura->setUdala($user->getUdala());
            $this->em->persist($fitxaProzedura);
            $this->em->flush();

            return $this->redirectToRoute('fitxaprozedura_show', ['id' => $fitxaProzedura->getId()]);
        }

        return $this->render('fitxaprozedura/new.html.twig', ['fitxaProzedura' => $fitxaProzedura, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a FitxaProzedura entity.
     */
    #[Route(path: '/{id}', name: 'fitxaprozedura_show', methods: ['GET'])]
    public function show(FitxaProzedura $fitxaProzedura): Response
    {
        $deleteForm = $this->createDeleteForm($fitxaProzedura);

        return $this->render('fitxaprozedura/show.html.twig', ['fitxaProzedura' => $fitxaProzedura, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing FitxaProzedura entity.
     */
    #[Route(path: '/{id}/edit', name: 'fitxaprozedura_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FitxaProzedura $fitxaProzedura)
    {
        $deleteForm = $this->createDeleteForm($fitxaProzedura);
        $editForm = $this->createForm(FitxaProzeduraType::class, $fitxaProzedura);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em->persist($fitxaProzedura);
            $this->em->flush();

            return $this->redirectToRoute('fitxaprozedura_edit', ['id' => $fitxaProzedura->getId()]);
        }

        return $this->render('fitxaprozedura/edit.html.twig', ['fitxaProzedura' => $fitxaProzedura, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);

    }

    /**
     * Deletes a FitxaProzedura entity.
     */
    #[Route(path: '/{id}', name: 'fitxaprozedura_delete', methods: ['DELETE'])]
    public function delete(Request $request, FitxaProzedura $fitxaProzedura): RedirectResponse
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
     * @return Form The form
     */
    private function createDeleteForm(FitxaProzedura $fitxaProzedura)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fitxaprozedura_delete', ['id' => $fitxaProzedura->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
