<?php

use Phalcon\Mvc\Model;

class Settings extends Model
{
    public $id;
    public $title_optimization;
    public $default_price;
    public $default_stock;
    public $default_zipcode;
    public $is_enabled;
}