<?php


namespace rating;


use MicroweberPackages\DatabaseManager\Crud;

class Model extends Crud
{

    public $app;
    public $table = 'rating';

    public function __construct($app = null)
    {
        $this->app = mw();
    }
}
