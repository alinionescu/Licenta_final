<?php
/**
 * Created by PhpStorm.
 * User: alin
 * Date: 13.03.2016
 * Time: 15:36
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use Avanzu\AdminThemeBundle\Event\ShowUserEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class MyShowUserListener
{
    /** @var TokenStorage */
    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param ShowUserEvent $event
     */
    public function onShowUser(ShowUserEvent $event)
    {
        $user = $this->getUser();
        $event->setUser($user);
    }

    /**
     * @return mixed
     */
    protected function getUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}