<?php
/**
 * Created by PhpStorm.
 * User: alin.ionescu
 * Date: 14.06.2016
 * Time: 21:04
 */

namespace AppBundle\Service;


use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class SecurityService
{
    /** @var TokenStorage */
    private $tokenStorage;

    /** @var AuthorizationChecker */
    private $authorizationChecker;

    public function __construct(TokenStorage $tokenStorage, AuthorizationChecker $authorizationChecker)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            /** @var User $user */
            $user = $this->tokenStorage->getToken()->getUser();

            $type = $user->getPerson()->getPersonType()->getId();

            if ($type === \AppBundle\Entity\PersonType::PERSON_TYPE_ADMIN) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isProfesor()
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            /** @var User $user */
            $user = $this->tokenStorage->getToken()->getUser();

            $type = $user->getPerson()->getPersonType()->getId();

            if ($type === \AppBundle\Entity\PersonType::PERSON_TYPE_PROFESOR) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isStudent()
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            /** @var User $user */
            $user = $this->tokenStorage->getToken()->getUser();

            $type = $user->getPerson()->getPersonType()->getId();

            if ($type === \AppBundle\Entity\PersonType::PERSON_TYPE_STUDENT) {
                return true;
            }
        }

        return false;
    }
}