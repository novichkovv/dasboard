<?php
/**
 * Created by PhpStorm.
 * User: asus1
 * Date: 25.05.2015
 * Time: 1:19
 */
class index_controller extends controller
{
    private $template = '';

    public function index()
    {
        if($_GET['rand']) {
            $this->randomData();
        }
        $permitted_charts = $this->model('charts')->getPermittedChartsList();
        foreach($permitted_charts as $chart) {
            $method_name = $chart['chart_key'];
            if(method_exists($this, $method_name)) {
                $this->$method_name();
                $this->template .= $this->fetch('charts' . DS . $method_name);
            } else {
                $this->writeLog('ERRORS', 'WRONG chart method "' . $method_name . '" called! ');
            }
        }
        $this->render('template', $this->template);
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

    private function active_projects()
    {

    }

    private function team_member_hours()
    {

    }

    private function team_member_table()
    {

    }

    private function utilization()
    {

    }

    private function week()
    {

    }

    private function project_detail()
    {

    }

    private function project_cost()
    {

    }

    private function overall()
    {

    }

    private function randomData()
    {
        $date_start = '2015-06-01';
        $date_end = date('Y-m-d');
        for($i = strtotime($date_start); $i <= strtotime($date_end); $i += 3600*24) {
            $row = [];
            foreach ($this->model('user_mapping')->getAll() as $user) {
                $row['email'] = $user['user_name'];
            }

        }
        exit;
    }
}