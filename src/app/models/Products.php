<?php

use Phalcon\Mvc\Model;

class Products extends Model
{
    public $id;
    public $title;
    public $description;
    public $tags;
    public $price;
    public $stock;

    // public function beforeCreate()
    // {
    //     $setting = Settings::findFirst();
    //     if($setting)
    //     var_dump($setting->title_optimization);die;
    //     $date     = date('YmdHis');
    //     echo $this->title; echo $this->tags;
    //     echo $date; die("hereein model");
    // }
}