<?php
namespace App\Components;

use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;

class AclManager
{
    public function manage(){
        $acl = new Memory();

        $acl->addRole('admin');
        $acl->addRole('guest');
        $acl->addRole('manager');

        $acl->addComponent(
            'order',
            [
                'add',
                'list',
            ]
        );

        $acl->addComponent(
            'product',
            [
                'list',
                'add',
            ]
        );

        $acl->allow('admin', 'product', ['list', 'add']);
        $acl->allow('admin', 'order', ['list', 'add']);

        $acl->allow('guest', 'product', 'list');
        $acl->allow('guest', 'order', 'list');
        return $acl;
    }
}