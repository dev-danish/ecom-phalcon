<?php
namespace App\Components;

use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;
use Phalcon\Mvc\Model\Roles;
use Phalcon\Mvc\Model\Aclmanage;


class AclManager
{
    public function manage($roles, $resources, $components){
        $acl = new Memory();

        foreach($roles as $role){
            $acl->addRole($role->role);
            // echo $role->role;
        }
        $mainArray = [];
        foreach($components as $component){
            $tempArray = [];
            $role = $component->role;
            $tempArray['role'] = $role;
            $resources = $component->resources;
            $resources = explode(",", $resources);
            $oldArray=[];
            foreach($resources as $resource){
                $resource = explode("_", $resource);
                $controller = $resource[0];
                $action = $resource[1];

                if(array_key_exists($controller, $tempArray)){
                    $oldArray = $tempArray[$controller];
                    if(!in_array($action, $oldArray)){
                        array_push($oldArray, $action);
                        $tempArray[$controller] = $oldArray;
                    }
                    // print_r($oldArray);die;
                }
                else{
                    $actionArray = [];
                    array_push($actionArray, $action);
                    $tempArray[$controller] = $actionArray;
                }
            }
            // echo "<pre>";
            // print_r($tempArray);
            // array_push($tempArray, $mainArray);
            // echo $role;
            
            foreach($tempArray as $key => $value){
                if($key == 'role'){
                    $role = $value;
                }
                else{
                    $acl->addComponent(
                        $key,
                        $value
                    );
                    $acl->allow($role, $key, $value);
                }
            }
        }
        
        // die;

        // $acl->addRole('admin');
        // $acl->addRole('guest');
        // $acl->addRole('manager');

        // $acl->addComponent(
        //     'order',
        //     [
        //         'add',
        //         'list',
        //     ]
        // );

        // $acl->addComponent(
        //     'product',
        //     [
        //         'list',
        //         'add',
        //     ]
        // );

        // $acl->allow('admin', 'product', ['list', 'add']);
        // $acl->allow('admin', 'order', ['list', 'add']);

        // $acl->allow('guest', 'product', 'list');
        // $acl->allow('guest', 'order', 'list');
        return $acl;
    }
}