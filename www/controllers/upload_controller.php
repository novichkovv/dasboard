<?php
/**
 * Created by PhpStorm.
 * User: enovichkov
 * Date: 07.08.2015
 * Time: 13:55
 */
class upload_controller extends controller
{
    public function index()
    {
        $this->view('upload' . DS . 'index');
    }
}