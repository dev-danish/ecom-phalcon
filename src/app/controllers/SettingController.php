<?php 

use Phalcon\Mvc\Controller;

class SettingController extends Controller
{
    public function indexAction(){
        echo "Settings";
    }
    public function addAction(){
        $setting = new Settings();
        $inputData = [
            "id" => time(),
            "title_optimization" => $this->request->getPost("title_optimization"),
            "default_price" => $this->request->getPost("default_price"),
            "default_stock" => $this->request->getPost("default_stock"),
            "default_zipcode" => $this->request->getPost("default_zipcode")
        ];
        if($this->request->getQuery("submit")){
            $setting->assign(
                $inputData,
                [
                    "id",
                    "title_optimization",
                    "default_price",
                    "default_stock",
                    "default_zipcode",
                ]
            );
    
            $success = $setting->save();

            $this->view->success = $success;
            $this->view->submit = $submit = true;
            if($success){
                $this->view->message = "Setting created succesfully";
            }else{
                $this->view->message = "Internal server error";
            }
        }
        else{
            $this->view->submit = $submit = false;
        }
    }

    public function listAction(){
        $this->view->settings = Settings::find();
    }
}