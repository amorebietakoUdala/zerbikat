<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Ordenantzaparrafoa;
use App\Form\OrdenantzaparrafoaType;
use App\Repository\OrdenantzaparrafoaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Ordenantzaparrafoa controller.
 */
#[Route(path: '/{_locale}/ordenantzaparrafoa')]
class OrdenantzaparrafoaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private OrdenantzaparrafoaRepository $repo
    )
    {
    }

    /**
     * Lists all Ordenantzaparrafoa entities.
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/', name: 'ordenantzaparrafoa_index', methods: ['GET'])]
    public function index()
    {
        $ordenantzaparrafoas = $this->repo->findAll();

        $deleteForms = [];
        foreach ($ordenantzaparrafoas as $ordenantzaparrafoa) {
            $deleteForms[$ordenantzaparrafoa->getId()] = $this->createDeleteForm($ordenantzaparrafoa)->createView();
        }

        return $this->render('ordenantzaparrafoa/index.html.twig', ['ordenantzaparrafoas' => $ordenantzaparrafoas, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Ordenantzaparrafoa entity.
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/new', name: 'ordenantzaparrafoa_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $ordenantzaparrafoa = new Ordenantzaparrafoa();
        $form = $this->createForm(OrdenantzaparrafoaType::class, $ordenantzaparrafoa);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ordenantzaparrafoa = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $ordenantzaparrafoa->setCreatedAt(new \DateTime());
            $ordenantzaparrafoa->setUpdatedAt(new \DateTime());
            $ordenantzaparrafoa->setUdala($user->getUdala());
            $this->em->persist($ordenantzaparrafoa);
            $this->em->flush();

            return $this->redirectToRoute('ordenantzaparrafoa_index');
        }

        return $this->render('ordenantzaparrafoa/new.html.twig', ['ordenantzaparrafoa' => $ordenantzaparrafoa, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Ordenantzaparrafoa entity.
     */
    #[Route(path: '/{id}', name: 'ordenantzaparrafoa_show', methods: ['GET'])]
    public function show(Ordenantzaparrafoa $ordenantzaparrafoa): Response
    {
        $deleteForm = $this->createDeleteForm($ordenantzaparrafoa);

        return $this->render('ordenantzaparrafoa/show.html.twig', ['ordenantzaparrafoa' => $ordenantzaparrafoa, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Ordenantzaparrafoa entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'ordenantzaparrafoa_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ordenantzaparrafoa $ordenantzaparrafoa)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($ordenantzaparrafoa->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Ordenantzaparrafoa entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]    
    #[Route(path: '/{id}', name: 'ordenantzaparrafoa_delete', methods: ['DELETE'])]
    public function delete(Request $request, Ordenantzaparrafoa $ordenantzaparrafoa): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($ordenantzaparrafoa->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');
        }            
    }

    /**
     * Creates a form to delete a Ordenantzaparrafoa entity.
     *
     * @param Ordenantzaparrafoa $ordenantzaparrafoa The Ordenantzaparrafoa entity
     *
     * @return Form The form
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
