<?php
// src/Zerbikat/BackendBundle/Controller/AdminController.php
namespace App\Controller;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use AMREU\UserBundle\Doctrine\UserManager;

class AdminController extends BaseAdminController
{
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager= $userManager;
    }

    
    // TODO Implementar este método en el AMREU UserManager?
    // public function createNewUserEntity()
    // {
    //     return $this->userManager->createUser();
    // }

    public function prePersistUserEntity($user)
    {
        $this->userManager->updateUser($user, false);
    }

    public function preUpdateUserEntity($user)
    {
        $this->userManager->updateUser($user, false);
    }

}