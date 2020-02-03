<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.01.2020
 * Time: 9:18
 */

namespace Timetracker\Security;

use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Acl\Component;
use Phalcon\Acl\Role;
use Phalcon\Acl\Enum;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;

class SecurityPlugin extends Injectable
{

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $auth = $this->session->get('AUTH');

        if ($auth == 'admin') {
            $role = 'Admin';
        } else if($auth == 'users') {
            $role = 'Users';
        } else if (!$auth) {
            $role = 'Guests';
        }

        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        /* Debugbar start */
        $ns = $dispatcher->getNamespaceName();
        if ($ns=='Snowair\Debugbar\Controllers') {
            return true;
        }
        /* Debugbar end */

        $acl = $this->getAcl();

        if (!$acl->isComponent($controller)) {
            $dispatcher->forward([
                'controller' => 'errors',
                'action'     => 'show404',
            ]);

            return false;
        }

        $allowed = $acl->isAllowed($role, $controller, $action);
        if (!$allowed) {
            $dispatcher->forward([
                'controller' => 'errors',
                'action'     => 'route401',
            ]);

            $this->session->destroy();

            return false;
        }

        return true;
    }

    protected function getAcl(): AclList
    {

        $acl = new AclList();
        $acl->setDefaultAction(Enum::DENY);

        $roles = [
            'admin' => new Role(
               'Admin',
               'superuser'
            ),
            'users'  => new Role(
                'Users',
                'Member privileges, granted after sign in.'
            ),
            'guests' => new Role(
                'Guests',
                'Anyone browsing the site who is not signed in is considered to be a "Guest".'
            )
        ];

        foreach ($roles as $role) {
            $acl->addRole($role);
        }

        //Private area resources
        $privateResources = [
            'admin'    => ['index', 'register', 'registerSubmit', 'disableUser', 'usersManagement'],
        ];

        foreach ($privateResources as $resource => $actions) {
            $acl->addComponent(new Component($resource), $actions);
        }

        //Public area resources
        $publicResources = [
            'index'      => ['index'],
            'users'      => [
                'index',
                'login',
                'loginSubmit',
                'profile',
                'logout',
                'search',
                'edit',
                'create',
                'create',
                'delete',
                'workTable',
                'ajaxPost'
            ],
            'errors'     => ['show401', 'show404', 'show500']
        ];

        foreach ($publicResources as $resource => $actions) {
            $acl->addComponent(new Component($resource), $actions);
        }

        //Grant access to public areas to both users and guests
        foreach ($roles as $role) {
            foreach ($publicResources as $resource => $actions) {
                foreach ($actions as $action) {
                    $acl->allow($role->getName(), $resource, $action);
                }
            }
        }

        //Grant access to private area to role Users
        foreach ($privateResources as $resource => $actions) {
            foreach ($actions as $action) {
                $acl->allow('Admin', $resource, $action);
            }
        }

        return $acl;
    }
}