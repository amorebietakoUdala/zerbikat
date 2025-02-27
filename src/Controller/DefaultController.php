<?php

namespace App\Controller;

use App\Repository\EremuakRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
 
    public function __construct(private EntityManagerInterface $em, private EremuakRepository $eremuakRepo)
    {        
    }
    /**
     * Index
     */
    #[Route(path: '/')]
    public function index(): Response
    {
        if ( $this->isGranted("ROLE_ADMIN") ) {
            return $this->redirectToRoute('admin_user_index');
        }
        return $this->redirectToRoute('fitxa_index');
    }

    
}
