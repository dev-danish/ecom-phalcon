<?php 

use Phalcon\Mvc\Controller;

class OrderController extends Controller
{
    public function indexAction(){
        echo "product";
    }
    public function addAction(){
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