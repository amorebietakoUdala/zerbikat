<?php
// src/Zerbikat/BackendBundle/Controller/AdminController.php
namespace App\Controller;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use FOS\UserBundle\Model\UserManagerInterface;

class AdminController extends BaseAdminController
{
    private $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager= $userManager;
    }

    public function createNewUserEntity()
    {
        return $this->userManager->createUser();
    }

    public function prePersistUserEntity($user)
    {
        $this->userManager->updateUser($user, false);
    }

    public function preUpdateUserEntity($user)
    {
        $this->userManager->updateUser($user, false);
    }

}