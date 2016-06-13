<?php
/**
 * Created by PhpStorm.
 * User: alin
 * Date: 31.03.2016
 * Time: 19:00
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Person;
use AppBundle\Entity\User;
use AppBundle\Form\PersonType;
use AppBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class AdminController extends Controller
{
    /**
     * @return type
     */
    public function addUserAction()
    {
        return $this->redirectToRoute('app_registration');
    }

    /**
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function promoteUserListAction()
    {
        /** @var AuthorizationChecker $authorizationChecker */
        $authorizationChecker = $this->container->get('security.authorization_checker');

        if ($authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            /** @var TokenStorage $tokenStorage */
            $tokenStorage = $this->container->get('security.token_storage');

            /** @var User $user */
            $user = $tokenStorage->getToken()->getUser();

            $type = $user->getPerson()->getPersonType()->getId();
            if ($type !== \AppBundle\Entity\PersonType::PERSON_TYPE_ADMIN) {
                return $this->render(':admin:accessDenied.html.twig');
            }

            /** @var UserRepository $userRepository */
            $userRepository = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:User');

            $users = $userRepository->findAll();

            return $this->render(':admin:listUser.html.twig',
                array(
                    'messages' => "",
                    'users' => $users
                )
            );
        }

        $url = $this->generateUrl('fos_user_security_login');
        $response = new RedirectResponse($url);

        return $response;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function promoteUserAction(Request $request)
    {


        $role = $request->get('role');
        $userId = $request->get('id');

        /** @var UserRepository $userRepository */
        $userRepository = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:User');

        /** @var User $user */
        $user = $userRepository->findOneBy(array('id' => $userId));

        if ($user == null || !($user instanceof User)) {
            $this->get('session')->getFlashBag()->add('error', 'User Does Not Exist');
        }

        $type = $user->getPerson()->getPersonType()->getType();

        /** @var AuthorizationChecker $authorizationChecker */
        $authorizationChecker = $this->container->get('security.authorization_checker');
        if (!$authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($type !== \AppBundle\Entity\PersonType::PERSON_TYPE_ADMIN) {
                return $this->render(':admin:accessDenied.html.twig');
            }
        }

        $userManager = $this->get('fos_user.util.user_manipulator');
        $userManager->addRole($user, $role);

        $users = $userRepository->findAll();

        return $this->render(':admin:listUser.html.twig',
            array(
                'users' => $users
            )
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function demoteUserAction(Request $request)
    {
        /** @var AuthorizationChecker $authorizationChecker */
        $authorizationChecker = $this->container->get('security.authorization_checker');
        if (!$authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            if (!$authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
                return $this->render(':admin:accessDenied.html.twig');
            }
        }

        $role = $request->get('role');
        $userId = $request->get('id');

        /** @var UserRepository $userRepository */
        $userRepository = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:User');

        /** @var User $user */
        $user = $userRepository->findOneBy(array('id' => $userId));

        if ($user == null || !($user instanceof User)) {
            $this->get('session')->getFlashBag()->add('error', 'User Does Not Exist');
        }

        $userManager = $this->get('fos_user.util.user_manipulator');
        $userManager->removeRole($user, $role);

        $users = $userRepository->findAll();

        $this->get('session')->getFlashBag()->add('success', 'User Done Demote');
        return $this->render(':admin:listUser.html.twig',
            array(
                'users' => $users
            )
        );
    }

    public function editUserAction(Request $request)
    {

    }
}
