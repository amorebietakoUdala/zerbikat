<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
 
    /**
     * Index
     *
     * @Route("/")
     */
    public function index(): Response
    {
        if ( $this->isGranted("ROLE_ADMIN") ) {
            return $this->redirectToRoute('admin_user_list');
        }
        return $this->redirectToRoute('fitxa_index');
    }

    /**
     * Errorea
     *
     * @Route("/errorea")
     */
    public function errorea(): Response
    {
        return $this->render('App:Default:errorea.html.twig');

    }
}
