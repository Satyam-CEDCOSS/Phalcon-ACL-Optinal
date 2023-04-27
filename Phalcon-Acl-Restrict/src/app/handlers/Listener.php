<?php

namespace MyApp\Listener;

use Phalcon\Di\Injectable;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Application;
use Phalcon\Events\Event;


class Listener extends Injectable
{
    public function beforeHandleRequest(Event $events, Application $app, Dispatcher $dis)
    {
        $di = $this->getDI();

        $acl = new Memory();

        $acl->addRole('manager');
        $acl->addRole('accountant');
        $acl->addRole('user');
        $acl->addRole('guest');

        $acl->addComponent(
            '',
            [
                ''
            ]
        );

        $acl->addComponent(
            'admin',
            [
                'index'
            ]
        );

        $acl->addComponent(
            'account',
            [
                'index'
            ]
        );

        $acl->addComponent(
            'login',
            [
                'index',
                'login'
            ]
        );

        $acl->addComponent(
            'signup',
            [
                'index',
                'register'
            ]
        );

        $acl->addComponent(
            'user',
            [
                'index'
            ]
        );

        $acl->allow('manager', '*', '*');
        $acl->allow('*', '', '*');
        $acl->allow('*', 'login', '*');
        $acl->allow('*', 'signup', '*');
        $acl->allow('user', 'user', '*');
        $acl->allow('accountant', 'account', '*');

        if (!($di->get('session')->get('type'))) {
            $di->get('session')->set('type', 'guest');
        }

        $check = $acl->isAllowed($di->get('session')->get('type'), $dis->getControllerName(), $dis->getActionName());
        if (!$check) {
            echo "Access Denied";
            $value = $di->get('session')->get('count');
            $di->get('session')->set('count', $value - 1);
            die;
        }
        if ($di->get('session')->get('type') == 'user' && $di->get('session')->get('count') < 1) {
            $acl->deny('user', 'user', '*');
            echo "You Have Been Blocked";
            die;
        }
    }
}
