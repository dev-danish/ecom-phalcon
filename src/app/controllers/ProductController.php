<?php 

use Phalcon\Mvc\Controller;
use Phalcon\Events\Event;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

class ProductController extends Controller 
{
    public function indexAction(){
        echo "product";
    }
    public function addAction(){
        $product = new Products();
        $inputData = [
            "id" => time(),
            "title" => $this->request->getPost("title"),
            "description" => $this->request->getPost("description"),
            "tags" => $this->request->getPost("tags"),
            "price" => $this->request->getPost("price") == "" ? 0 : $this->request->getPost("price"),
            "stock" => $this->request->getPost("stock") == "" ? 0 : $this->request->getPost("stock")
        ];
        if($this->request->getQuery("submit")){
            $product->assign(
                $inputData,
                [
                    "id",
                    "title",
                    "description",
                    "tags",
                    "price",
                    "stock"
                ]
            );
            $success = $product->save();
            
            $this->view->success = $success;
            $this->view->submit = $submit = true;
            if($success){
                $this->view->message = "Product created succesfully";
            }else{
                $this->view->message = "Internal server error";
            }
        }
        else{
            $this->view->submit = $submit = false;
        }
    }

    public function listAction(){
        $this->view->products = Products::find();
    }
}