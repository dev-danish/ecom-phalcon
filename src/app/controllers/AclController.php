<?php 

use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;

class AclController extends Controller
{
    public function indexAction(){

        $acl = new Memory();

        /**
         * Add the roles
         */
        $acl->addRole('admin');
        $acl->addRole('guest');
        $acl->addRole('manager');

        /**
         * Add the Components
         */

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

        /**
         * Now tie them all together 
         */
        $acl->allow('admin', 'product', '*');
        $acl->allow('admin', 'order', '*');

        $acl->allow('guest', 'product', 'list');
        $acl->allow('guest', 'order', 'list');

        echo $acl->isAllowed('guest', 'product', 'list');
        echo "Acl cont";
    }
    public function addroleAction(){
        $order = new Orders();
        $inputData = [
            "id" => time(),
            "customer_name" => $this->request->getPost("customer_name"),
            "customer_address" => $this->request->getPost("customer_address"),
            "zipcode" => $this->request->getPost("zipcode") == "" ? 0 : $this->request->getPost("zipcode"),
            "product" => $this->request->getPost("product"),
            "quantity" => $this->request->getPost("quantity")
        ];
        if($this->request->getQuery("submit")){
            $order->assign(
                $inputData,
                [
                    "id",
                    "customer_name",
                    "customer_address",
                    "zipcode",
                    "product",
                    "quantity"
                ]
            );
    
            $success = $order->save();

            $this->view->success = $success;
            $this->view->submit = $submit = true;
            if($success){
                $this->view->message = "Order created succesfully";
            }else{
                $this->view->message = "Internal server error";
            }
        }
        else{
            $this->view->products = Products::find();
            $this->view->submit = $submit = false;
        }
    }

    public function listAction(){
        $this->view->orders = Orders::find();
    }
}