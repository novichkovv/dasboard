<?php
/**
 * Created by PhpStorm.
 * User: asus1
 * Date: 25.05.2015
 * Time: 1:19
 */
class index_controller extends controller
{
    public function index()
    {
        //$this->render('modules', $this->model('modules')->getGroupModel());
        $this->view('index');
    }

    public function index_ajax()
    {
        switch($_REQUEST['action']) {
            case "save_modules_position":
                $this->model('modules')->savePositions($_POST['positions']);
                exit;
                break;
        }
    }

    public function index_na()
    {
        $this->sidebar = false;
        $this->header = false;
        $this->footer = false;
        $this->view('index_na');
    }
}