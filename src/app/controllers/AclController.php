<?php 

use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;

class AclController extends Controller
{
    public function indexAction(){
        die("roles");
        // $acl = new Memory();

        // /**
        //  * Add the roles
        //  */
        // $acl->addRole('admin');
        // $acl->addRole('guest');
        // $acl->addRole('manager');

        // /**
        //  * Add the Components
        //  */

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

        // /**
        //  * Now tie them all together 
        //  */
        // $acl->allow('admin', 'product', '*');
        // $acl->allow('admin', 'order', '*');

        // $acl->allow('guest', 'product', 'list');
        // $acl->allow('guest', 'order', 'list');

        // echo $acl->isAllowed('guest', 'product', 'list');
        // echo "Acl cont";
    }
    public function roleAction(){
        $role = new Roles();
        $inputData = [
            "id" => time(),
            "role" => $this->request->getPost("role")
        ];
        if($this->request->getQuery("submit")){
            $role->assign(
                $inputData,
                [
                    "id",
                    "role"
                ]
            );
    
            $success = $role->save();

            $this->view->success = $success;
            $this->view->submit = $submit = true;
            $this->view->roles = Roles::find();
            if($success){
                $this->view->message = "Role created succesfully";
            }else{
                $this->view->message = "Internal server error";
            }
        }
        else{
            $this->view->roles = Roles::find();
            $this->view->submit = $submit = false;
        }
    }

    public function resourceAction(){
        $resource = new Resources();
        $inputData = [
            "id" => time(),
            "resource" => $this->request->getPost("controller")."_".$this->request->getPost("action")
        ];
        if($this->request->getQuery("submit")){
            $resource->assign(
                $inputData,
                [
                    "id",
                    "resource"
                ]
            );
    
            $success = $resource->save();

            $this->view->success = $success;
            $this->view->submit = $submit = true;
            $this->view->resources = Resources::find();
            if($success){
                $this->view->message = "Resource created succesfully";
            }else{
                $this->view->message = "Internal server error";
            }
        }
        else{
            $this->view->resources = Resources::find();
            $this->view->submit = $submit = false;
        }
    }
    public function manageAction(){
        if($this->request->getQuery("submit")){
            $aclmanager = new Aclmanage();
            $inputData = [
                "id" => time(),
                "role" => $this->request->getPost("role")
            ];
            $components = "";
            $count=0;
            foreach($this->request->getPost() as $key => $value){
                $count++;
                if($key == "role") continue;
                if($count == count($this->request->getPost())){
                    $components .= $value;
                }
                else{
                    $components .= $value.",";
                }
            }
            $inputData["resources"] = $components;
            $aclmanager->assign(
                $inputData,
                [
                    "id",
                    "role",
                    "resources"
                ]
            );

            $success = $aclmanager->save();
            $this->view->success = $success;
            $this->view->submit = $submit = true;
        }
        else{
            $this->view->submit = $submit = false;
        }
        $resources = Resources::find();
        $this->view->roles = Roles::find();
        $this->view->resources = $resources;
        $this->view->aclmanage = Aclmanage::find();

        
        // $controllers = [];
        // foreach($resources as $resource) {
        //     $controller = $resource->controller;
        //     if(array_key_exists($controller, $controllers)) continue;
        //     $controllers[$controller] = $resource->action;
        // }
        // var_dump($controllers);die;
    }
}