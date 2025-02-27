<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Kalea;
use App\Form\KaleaType;
use App\Repository\KaleaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Kalea controller.
 */
#[Route(path: '/{_locale}/kalea')]
class KaleaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private KaleaRepository $repo
    )
    {
    }

    /**
     * Lists all Kalea entities.
     */
    #[IsGranted('ROLE_KUDEAKETA')]
    #[Route(path: '/', name: 'kalea_index', methods: ['GET'])]
    public function index()
    {
        $kaleas = $this->repo->findAll();

        $deleteForms = [];
        foreach ($kaleas as $kalea) {
            $deleteForms[$kalea->getId()] = $this->createDeleteForm($kalea)->createView();
        }

        return $this->render('kalea/index.html.twig', ['kaleas' => $kaleas, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Kalea entity.
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/new', name: 'kalea_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {

        $kalea = new Kalea();
        $form = $this->createForm(KaleaType::class, $kalea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $kalea = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $kalea->setUdala($user->getUdala());
            $this->em->persist($kalea);
            $this->em->flush();

//                return $this->redirectToRoute('kalea_show', array('id' => $kalea->getId()));
            return $this->redirectToRoute('kalea_index');
        }

        return $this->render('kalea/new.html.twig', ['kalea' => $kalea, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Kalea entity.
     */
    #[Route(path: '/{id}', name: 'kalea_show', methods: ['GET'])]
    public function show(Kalea $kalea): Response
    {
        $deleteForm = $this->createDeleteForm($kalea);

        return $this->render('kalea/show.html.twig', ['kalea' => $kalea, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Kalea entity.
     */
    #[Route(path: '/{id}/edit', name: 'kalea_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Kalea $kalea)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($kalea->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');
        }            
    }

    /**
     * Deletes a Kalea entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}', name: 'kalea_delete', methods: ['DELETE'])]
    public function delete(Request $request, Kalea $kalea): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($kalea->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');
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
