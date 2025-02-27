<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Azpiatalaparrafoa;
use App\Form\AzpiatalaparrafoaType;
use App\Repository\AzpiatalaparrafoaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Azpiatalaparrafoa controller.
 */
#[Route(path: '/{_locale}/azpiatalaparrafoa')]
class AzpiatalaparrafoaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private AzpiatalaparrafoaRepository $repo
    )
    {
    }

    /**
     * Lists all Azpiatalaparrafoa entities.
     */
    #[Route(path: '/', name: 'azpiatalaparrafoa_index', methods: ['GET'])]
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
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/new', name: 'azpiatalaparrafoa_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $azpiatalaparrafoa = new Azpiatalaparrafoa();
        $form = $this->createForm(AzpiatalaparrafoaType::class, $azpiatalaparrafoa);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $azpiatalaparrafoa = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $azpiatalaparrafoa->setUdala($udala);                
//                $azpiatalaparrafoa->setCreatedAt(new \DateTime());
//                $azpiatalaparrafoa->setUpdatedAt(new \DateTime());
            $this->em->persist($azpiatalaparrafoa);
            $this->em->flush();

            return $this->redirectToRoute('azpiatalaparrafoa_show', ['id' => $azpiatalaparrafoa->getId()]);
        }

        return $this->render('azpiatalaparrafoa/new.html.twig', ['azpiatalaparrafoa' => $azpiatalaparrafoa, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Azpiatalaparrafoa entity.
     */
    #[Route(path: '/{id}', name: 'azpiatalaparrafoa_show', methods: ['GET'])]
    public function show(Azpiatalaparrafoa $azpiatalaparrafoa): Response
    {
        $deleteForm = $this->createDeleteForm($azpiatalaparrafoa);

        return $this->render('azpiatalaparrafoa/show.html.twig', ['azpiatalaparrafoa' => $azpiatalaparrafoa, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Azpiatalaparrafoa entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'azpiatalaparrafoa_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Azpiatalaparrafoa $azpiatalaparrafoa)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($azpiatalaparrafoa->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Azpiatalaparrafoa entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'azpiatalaparrafoa_delete', methods: ['DELETE'])]
    public function delete(Request $request, Azpiatalaparrafoa $azpiatalaparrafoa): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($azpiatalaparrafoa->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Creates a form to delete a Azpiatalaparrafoa entity.
     *
     * @param Azpiatalaparrafoa $azpiatalaparrafoa The Azpiatalaparrafoa entity
     *
     * @return Form The form
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
