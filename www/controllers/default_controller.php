<?php
/**
 * Created by PhpStorm.
 * User: asus1
 * Date: 25.05.2015
 * Time: 1:14
 */
class default_controller extends controller
{
    public function index()
    {
        $this->view_only('404');
    }

    public function dev()
    {
        $this->view_only('dev');
    }
}