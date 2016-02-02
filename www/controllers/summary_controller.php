<?php
/**
 * Created by PhpStorm.
 * User: asus1
 * Date: 27.01.2016
 * Time: 20:29
 */
class summary_controller extends controller
{
    public function index()
    {
        $dates = $this->dates();
        $users = [];
        foreach ($this->model('asanatt_user_mapping')->getAll() as $user) {
            $users[$user['id']] = $user;
        }
        registry::set('users', $users);
        $date_range = [];
        for($i = strtotime($dates['date_from']); $i <= strtotime($dates['date_to']); $i = $i + 3600*24) {
            $date_range[] = date('Y-m-d', $i);
        }
        $this->render('date_from', $dates['date_from']);
        $this->render('date_to', $dates['date_to']);
        $this->render('dates', $date_range);
        $this->render('users_list', $this->model('asanatt_user_mapping')->getAll('user_name'));
        if(empty($_POST['user'])) {
            $this->render('users', $this->model('asanatt_user_mapping')->getAll('user_name'));
        } else {
            $this->render('users', $this->model('asanatt_user_mapping')->getByField('id', $_POST['user'], true));
        }
        $this->render('late', $this->model('asanatt_excel_time')->getLateEmployees($dates));
        $this->render('absent', $this->model('asanatt_excel_time')->getAbsentEmployees($dates));
        //print_r($absent);
        $this->render('early', $this->model('asanatt_excel_time')->getEarlyFinished($dates));
        //print_r($this->model('asanatt_excel_time')->getLateEmployees($dates));
        //print_r($this->model('asanatt_excel_time')->getEarlyFinished($dates));
        $this->render('less_worked', $this->model('asanatt_excel_time')->getLessWorked($dates));
        $this->render('overtime', $this->model('asanatt_excel_time')->getOvertime($dates));
        //print_r($less_worked);
        //print_r($late);
        //print_r($absent);
        //print_r($early);
        //print_r($less_worked);
        //exit;
        $this->view('summary' . DS . 'index');
    }

    public function overtime()
    {
        if($this->model('asanatt_user_groups')->getById(registry::get('user')['user_group_id'])['can_approve']) {
            $this->render('allowed', true);
        }
        $dates = $this->dates();
        $date_range = [];
        for($i = strtotime($dates['date_from']); $i <= strtotime($dates['date_to']); $i = $i + 3600*24) {
            $date_range[] = date('Y-m-d', $i);
        }
        $this->render('date_from', $dates['date_from']);
        $this->render('date_to', $dates['date_to']);
        $this->render('dates', $date_range);
        $this->render('users_list', $this->model('asanatt_user_mapping')->getAll('user_name'));
        if(empty($_POST['user'])) {
            $this->render('users', $this->model('asanatt_user_mapping')->getAll('user_name'));
        } else {
            $this->render('users', $this->model('asanatt_user_mapping')->getByField('id', $_POST['user'], true));
        }
        $overtime = [];
        foreach ($this->model('asanatt_overtime')->getAll() as $v) {
            $overtime[$v['work_date']][$v['user_id']]['suggested'] = $v['overtime_suggested'];
            $overtime[$v['work_date']][$v['user_id']]['approved'] = $v['overtime_approved'];
            $overtime[$v['work_date']][$v['user_id']]['id'] = $v['id'];
        }
        $this->render('overtime', $overtime);
        $this->view('summary' . DS . 'overtime');
    }

    public function overtime_ajax()
    {
        if($this->model('asanatt_user_groups')->getById(registry::get('user')['user_group_id'])['can_approve']) {
            $this->render('allowed', true);
            $allowed = true;
        }
        switch ($_REQUEST['action']) {
            case "suggest_overtime":
                foreach ($_POST['overtime'] as $key => $val) {
                    if(!$val) {
                        echo json_encode(array('status' => 2));
                        exit;
                    }
                }
                $id = $this->model('asanatt_overtime')->insert($_POST['overtime']);
                $overtime = $this->model('asanatt_overtime')->getById($id);
                $this->render('overtime', $overtime);
                $template = $this->fetch('summary' . DS . 'ajax' . DS . 'overtime_table_cell');
                echo json_encode(array('status' => 1, 'date' => $overtime['work_date'], 'user_id' => $overtime['user_id'], 'template' => $template));
                exit;
                break;

            case "approve_overtime":
                if(!isset($_POST['overtime']['id']) || !isset($allowed)) {
                    echo json_encode(array('status' => 2));
                    exit;
                }
                $id = $this->model('asanatt_overtime')->insert($_POST['overtime']);
                $overtime = $this->model('asanatt_overtime')->getById($id);
                $this->render('overtime', $overtime);
                $template = $this->fetch('summary' . DS . 'ajax' . DS . 'overtime_table_cell');
                echo json_encode(array('status' => 1, 'date' => $overtime['work_date'], 'user_id' => $overtime['user_id'], 'template' => $template));
                exit;
                break;
        }
    }

    public function approve()
    {
        $this->view('summary' . DS . 'approve');
    }

    public function settings()
    {
        $this->render('config', registry::get('config'));
        $this->view('summary' . DS . 'settings');
    }

    public function settings_ajax()
    {
        switch ($_REQUEST['action']) {
            case "save_settings":
                foreach ($_POST['config'] as $key => $value) {
                    $row = $this->model('asanatt_system_config')->getByField('config_key', $key);
                    $row['config_key'] = $key;
                    $row['config_value'] = $value;
                    $this->model('asanatt_system_config')->insert($row);
                }
                echo json_encode(array('status' => 1));
                exit;
                break;
        }
    }
    
    private function dates()
    {
        $dates = [];
        if(isset($_POST['date_from'])) {
            $dates['date_from'] = $_POST['date_from'];
        } else {
            $dates['date_from'] = date('Y-m-01');
        }
        if(isset($_POST['date_to'])) {
            $dates['date_to'] = $_POST['date_to'];
        } else {
            $dates['date_to'] = date('Y-m-t');
        }
        return $dates;
    }
}