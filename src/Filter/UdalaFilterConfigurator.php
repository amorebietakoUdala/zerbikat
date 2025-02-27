<?php

namespace App\Filter;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class UdalaFilterConfigurator implements EventSubscriberInterface
{

    public function __construct(
        protected readonly EntityManagerInterface $em, 
        protected readonly TokenStorageInterface $tokenStorage, 
        protected readonly Security $security
    )
    {
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $user = $this->security->getUser();
        if (null !== $user) {
            /** @var SQLFilter $filter */
            $filter = $this->em->getFilters()->enable('udala_filter');
            /** @var User $user */
            if ($user->getUdala()) {
                $filter->setParameter('udala_id', $user->getudala()->getId());
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

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }
}