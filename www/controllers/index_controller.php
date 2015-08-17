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
                $data = $this->model('charts')->$method_name();
                $this->$method_name($data);
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

    private function active_projects($data)
    {

    }

    private function team_member_hours($data)
    {
        $stats = [];
        foreach($data as $k => $v) {
            $stats['data'][$k] = round($v['seconds'] / 3600);
            $stats['ticks'][$k] = $v['username'];
        }
        $this->render('stats', $stats);

    }

    private function team_member_table($data)
    {

    }

    private function utilization($data)
    {

    }

    private function week($data)
    {

    }

    private function project_detail($data)
    {

    }

    private function project_cost($data)
    {

    }

    private function overall($data)
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