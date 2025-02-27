<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Kontzeptua;
use App\Form\KontzeptuaType;
use App\Repository\KontzeptuaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Kontzeptua controller.
 */
#[Route(path: '/{_locale}/kontzeptua')]
class KontzeptuaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private KontzeptuaRepository $repo
    )
    {
    }

    /**
     * Lists all Kontzeptua entities.
     */
    #[IsGranted('ROLE_KUDEAKETA')]
    #[Route(path: '/', name: 'kontzeptua_index', methods: ['GET'])]
    public function index()
    {
        $kontzeptuas = $this->repo->findAll();

        $deleteForms = [];
        foreach ($kontzeptuas as $kontzeptua) {
            $deleteForms[$kontzeptua->getId()] = $this->createDeleteForm($kontzeptua)->createView();
        }

        return $this->render('kontzeptua/index.html.twig', ['kontzeptuas' => $kontzeptuas, 'deleteforms' => $deleteForms]);
    }

    /**
     * Creates a new Kontzeptua entity.
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/new', name: 'kontzeptua_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $kontzeptua = new Kontzeptua();
        $form = $this->createForm(KontzeptuaType::class, $kontzeptua);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $kontzeptua = $form->getData();
            /** @var User $user */
            $user = $this->getUser();
            $kontzeptua->setUdala($user->getUdala());
//                $kontzeptua->setCreatedAt(new \DateTime());
//                $kontzeptua->setUpdatedAt(new \DateTime());
            $this->em->persist($kontzeptua);
            $this->em->flush();

            return $this->redirectToRoute('kontzeptua_index');
        }

        return $this->render('kontzeptua/new.html.twig', ['kontzeptua' => $kontzeptua, 'form' => $form->createView()]);
    }

    /**
     * Finds and displays a Kontzeptua entity.
     */
    #[Route(path: '/{id}', name: 'kontzeptua_show', methods: ['GET'])]
    public function show(Kontzeptua $kontzeptua): Response
    {
        $deleteForm = $this->createDeleteForm($kontzeptua);

        return $this->render('kontzeptua/show.html.twig', ['kontzeptua' => $kontzeptua, 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Displays a form to edit an existing Kontzeptua entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]
    #[Route(path: '/{id}/edit', name: 'kontzeptua_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Kontzeptua $kontzeptua)
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($kontzeptua->getUdala()==$user->getUdala()))
            ||($this->isGranted('ROLE_SUPER_ADMIN')))
        {
            $deleteForm = $this->createDeleteForm($kontzeptua);
            $editForm = $this->createForm(KontzeptuaType::class, $kontzeptua);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($kontzeptua);
                $this->em->flush();

                return $this->redirectToRoute('kontzeptua_edit', ['id' => $kontzeptua->getId()]);
            }

            return $this->render('kontzeptua/edit.html.twig', ['kontzeptua' => $kontzeptua, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
        }else
        {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }

    /**
     * Deletes a Kontzeptua entity.
     */
    #[IsGranted(new Expression("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')"))]    
    #[Route(path: '/{id}', name: 'kontzeptua_delete', methods: ['DELETE'])]
    public function delete(Request $request, Kontzeptua $kontzeptua): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if((($this->isGranted('ROLE_ADMIN')) && ($kontzeptua->getUdala()==$user->getUdala()))
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
            throw new AccessDeniedHttpException('Access Denied');
        }            
    }

    /**
     * Creates a form to delete a Kontzeptua entity.
     *
     * @param Kontzeptua $kontzeptua The Kontzeptua entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Kontzeptua $kontzeptua)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('kontzeptua_delete', ['id' => $kontzeptua->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
