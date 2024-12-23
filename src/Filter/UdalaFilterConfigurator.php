<?php

namespace App\Filter;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class UdalaFilterConfigurator implements EventSubscriberInterface
{
    protected $em;
    protected $tokenStorage;
    protected $reader;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage, Reader $reader)
    {
        $this->em              = $em;
        $this->tokenStorage    = $tokenStorage;
        $this->reader          = $reader;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if ($user = $this->getUser()) {
            $filter = $this->em->getFilters()->enable('udala_filter');
            /** @var App\Entity\User $user */
            if ($user->getUdala()) {
                $filter->setParameter('udala_id', $user->getudala()->getId());
                $filter->setAnnotationReader($this->reader);
            }
        }
    }

    private function getUser()
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return null;
        }

        $user = $token->getUser();

        if (!($user instanceof UserInterface)) {
            return null;
        }

        return $user;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}