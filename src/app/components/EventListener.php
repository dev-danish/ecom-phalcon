<?php

namespace App\Components;

class EventListener 
{
    public function modelEvent($event, $model, $settings)
    {
        if (get_class($model) === "Products") {
            $product = $model;
            if($settings->title_optimization == "with_tags"){
                $product->title = $product->title."+".str_replace(",", "+", $product->tags);
            }
            if(count($product->price) == 0 || $product->price == "" || $product->price == 0){
                $product->price = $settings->default_price;
            }
            if(count($product->stock) == 0 || $product->stock == ""){
                $product->stock = $settings->default_stock;
            }
        }
        else if(get_class($model) === "Orders"){
            $order = $model;
            if($order->zipcode == 0 || count($order->zipcode) == 0){
                $order->zipcode = $settings->default_zipcode;
            }
        }
    }
}