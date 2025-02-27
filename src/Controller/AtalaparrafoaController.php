<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Atalaparrafoa;
use App\Form\AtalaparrafoaType;
use App\Repository\AtalaparrafoaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Atalaparrafoa controller.
 */
#[Route(path: '/{_locale}/atalaparrafoa')]
class AtalaparrafoaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private AtalaparrafoaRepository $repo
    )
    {
    }

    /**
     * Lists all Atalaparrafoa entities.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/', name: 'atalaparrafoa_index', methods: ['GET'])]
    public function index()
    {
        $atalaparrafoas = $this->repo->findAll();

        $deleteForms = [];
        foreach ($atalaparrafoas as $parrafoa) {
            $deleteForms[$parrafoa->getId()] = $this->createDeleteForm($parrafoa)->createView();
        }

        return $this->render('atalaparrafoa/index.html.twig', ['atalaparrafoas' => $atalaparrafoas, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Atalaparrafoa entity.
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route(path: '/new', name: 'atalaparrafoa_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $atalaparrafoa = new Atalaparrafoa();
        $form = $this->createForm(AtalaParrafoaType::class, $atalaparrafoa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $atalaparrafoa = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $udala = $user->getUdala();
            $atalaparrafoa->setUdala($udala);            
            $this->em->persist($atalaparrafoa);
            $this->em->flush();

            return $this->redirectToRoute('atalaparrafoa_show', ['id' => $atalaparrafoa->getId()]);
        }

        return $this->render('atalaparrafoa/new.html.twig', ['atalaparrafoa' => $atalaparrafoa, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Atalaparrafoa entity.
     */
    #[Route(path: '/{id}', name: 'atalaparrafoa_show', methods: ['GET'])]
    public function show(Atalaparrafoa $atalaparrafoa): Response
    {
        $deleteForm = $this->createDeleteForm($atalaparrafoa);

        return $this->render('atalaparrafoa/show.html.twig', ['atalaparrafoa' => $atalaparrafoa, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Atalaparrafoa entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]        
    #[Route(path: '/{id}/edit', name: 'atalaparrafoa_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Atalaparrafoa $atalaparrafoa)
    {
        /** @var User $user */
        $user = $this->getUser();        
        if((($this->isGranted('ROLE_ADMIN')) && ($atalaparrafoa->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');    
        }
    }

    /**
     * Deletes a Atalaparrafoa entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'atalaparrafoa_delete', methods: ['DELETE'])]
    public function delete(Request $request, Atalaparrafoa $atalaparrafoa): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();        
        if((($this->isGranted('ROLE_ADMIN')) && ($atalaparrafoa->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');            }
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
