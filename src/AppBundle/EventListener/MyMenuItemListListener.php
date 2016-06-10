<?php
/**
 * Created by PhpStorm.
 * User: alin
 * Date: 13.03.2016
 * Time: 19:55
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\PersonType;
use AppBundle\Entity\User;
use AppBundle\Model\MenuItemModel;
use Avanzu\AdminThemeBundle\Event\SidebarMenuEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class MyMenuItemListListener
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
     * @param SidebarMenuEvent $event
     */
    public function onSetupMenu(SidebarMenuEvent $event) {

        $request = $event->getRequest();

        foreach ($this->getMenu($request) as $item) {
            $event->addItem($item);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function getMenu(Request $request) {
        // Build your menu here by constructing a MenuItemModel array

        $menuItems = $this->getMeniuItemByRole();

        return $this->activateByRoute($request->get('_route'), $menuItems);
    }

    /**
     * @param $route
     * @param $items
     * @return mixed
     */
    protected function activateByRoute($route, $items) {

        foreach($items as $item) {
            if($item->hasChildren()) {
                $this->activateByRoute($route, $item->getChildren());
            }
            else {
                if($item->getRoute() == $route) {
                    $item->setIsActive(true);
                }
            }
        }

        return $items;
    }

    /**
     * @return array
     */
    protected function getMeniuItemByRole()
    {
        $menuItems = [];

        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            /** @var User $user */
            $user = $this->tokenStorage->getToken()->getUser();

            $type = $user->getPerson()->getPersonType()->getId();

            /** Meniu pentru Admin */
            if ($type === PersonType::PERSON_TYPE_ADMIN) {
                $admin = new MenuItemModel('AdministrareUtilizatori', 'Administrare utilizatori', '', array(/* options */), 'iconclasses fa fa-plane');
                $admin->addChild(new MenuItemModel('AdaugaUtilizator', 'Adauga utilizator', 'admin_add_user'));
                $admin->addChild(new MenuItemModel('StergeUtilizator', 'Sterge utilizator', 'fos_user_security_login'));
                $admin->addChild(new MenuItemModel('EditeazaUtilizator', 'Editeaza utilizator', 'fos_user_security_login'));
                $admin->addChild(new MenuItemModel('PromoteUtilizator', 'Promote utilizator', 'admin_promote_user_list'));

                array_push($menuItems, $admin);
            }

            /** Meniu pentru Profesor */
            if ($type === PersonType::PERSON_TYPE_PROFESOR) {
                $profesor = new MenuItemModel('DocumenteLicenta', 'Documente licenta', 'app_list_document', array(/* options */), 'iconclasses fa fa-plane');

                array_push($menuItems, $profesor);
            }

            /** Meniu pentru Student */
            if ($type === PersonType::PERSON_TYPE_STUDENT) {
                $student = new MenuItemModel('Student', 'Student', 'fos_user_security_login', array(/* options */), 'iconclasses fa fa-plane');

                array_push($menuItems, $student);
            }

            $blog = new MenuItemModel('ItemId', 'Primul item', '', array(/* options */), 'iconclasses fa fa-plane');
            $action = new MenuItemModel('actionId', 'Action', 'fos_user_security_login', array(), 'fa fa-rss-square');

            // A child with default circle icon
            $blog->addChild(new MenuItemModel('ChildTwoItemId', 'ChildTwoDisplayName', 'fos_user_security_login'));

             array_push($menuItems, $blog);
             array_push($menuItems, $action);

        } else {
            $login = new MenuItemModel('loginId', 'Login', 'fos_user_security_login', array(/* options */), 'iconclasses fa fa-plane');
            array_push($menuItems, $login);
        }

        return $menuItems;
    }
}