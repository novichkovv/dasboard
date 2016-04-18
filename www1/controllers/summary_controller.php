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
        $this->render('early', $this->model('asanatt_excel_time')->getEarlyFinished($dates));
        $this->render('less_worked', $this->model('asanatt_excel_time')->getLessWorked($dates));
        $this->render('overtime', $this->model('asanatt_excel_time')->getOvertime($dates));
        $this->view('summary' . DS . 'index');
    }

    public function overtime()
    {
        $user_id = $this->model('asanatt_user_mapping')->getByField('user_email', registry::get('user')['email'])['id'];
        $this->render('user_id', $user_id);
        if($this->model('asanatt_user_groups')->getById(registry::get('user')['user_group_id'])['can_approve']) {
            $this->render('allowed', true);
        } elseif($this->model('asanatt_user_groups')->getById(registry::get('user')['user_group_id'])['can_see']) {
            $this->render('can_see', true);
        } else {
            $dashboard_user = registry::get('user')['id'];
            $this->render('dashboard_user', $dashboard_user);
        }
        $dates = $this->dates();
        $date_range = [];
        for($i = strtotime($dates['date_from']); $i <= strtotime($dates['date_to']); $i = $i + 3600*24) {
            $date_range[] = date('Y-m-d', $i);
        }
        $this->render('date_from', $dates['date_from']);
        $this->render('date_to', $dates['date_to']);
        $this->render('dates', $date_range);
        if(!isset($dashboard_user)) {
            $this->render('users_list', $this->model('asanatt_user_mapping')->getAll('user_name'));
        } else {
            $this->render('users_list', $this->model('asanatt_user_mapping')->getByField('id', $user_id, true));
        }
        if(empty($_POST['user'])) {
            if(!$dashboard_user) {
                $this->render('users', $this->model('asanatt_user_mapping')->getAll('user_name'));
            } else {
                $this->render('users', $this->model('asanatt_user_mapping')->getByFields(array('id' => $user_id), true));
            }
        } else {
            if(!$dashboard_user) {
                $this->render('users', $this->model('asanatt_user_mapping')->getByField('id', $_POST['user'], true));
            } else {
                $this->render('users', $this->model('asanatt_user_mapping')->getByFields(array('id' => $user_id), true));
            }
        }
        $overtime = [];
        foreach ($this->model('asanatt_overtime')->getAll() as $v) {
            $overtime[$v['work_date']][$v['user_id']]['suggested'] = $v['overtime_suggested'];
            $overtime[$v['work_date']][$v['user_id']]['approved'] = $v['overtime_approved'];
            $overtime[$v['work_date']][$v['user_id']]['comments'] = $v['comments'];
            $overtime[$v['work_date']][$v['user_id']]['dashboard_user_id'] = $v['dashboard_user_id'];
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
                    if($val === '' && !in_array($key, array('comments', 'id'))) {
                        echo json_encode(array('status' => 2));
                        exit;
                    }
                }
                $overtime = $_POST['overtime'];
                $overtime['dashboard_user_id'] = registry::get('user')['id'];
                $id = $this->model('asanatt_overtime')->insert($overtime);
                $overtime = $this->model('asanatt_overtime')->getById($id);
                $this->render('overtime', $overtime);
                $template = $this->fetch('summary' . DS . 'ajax' . DS . 'overtime_table_cell');
                echo json_encode(array('status' => 1, 'date' => $overtime['work_date'], 'user_id' => $overtime['user_id'], 'template' => $template));
                exit;
                break;

            case "delete_overtime":
                $overtime = $this->model('asanatt_overtime')->getById($_POST['overtime']['id']);
                $this->model('asanatt_overtime')->deleteById($overtime['id']);
                unset($overtime['id']);
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

            case "get_overtime":
                $overtime = $this->model('asanatt_overtime')->getById($_POST['overtime_id']);
                echo json_encode(array('status' => 1, 'overtime' => $overtime));
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
            if($_SESSION['date_start']) {
                $dates['date_from'] = date('Y-m-d', strtotime($_SESSION['date_start']));
            } else {
                $dates['date_from'] = date('Y-m-01');
            }
        }
        $_SESSION['date_start'] = $dates['date_from'] . ' 00:00:00';
        if(isset($_POST['date_to'])) {
            $dates['date_to'] = $_POST['date_to'];
        } else {
            if($_SESSION['date_end']) {
                $dates['date_to'] = date('Y-m-d', strtotime($_SESSION['date_end']));
            } else {
                $dates['date_to'] = date('Y-m-t');
            }
        }
        if (strtotime($dates['date_to']) - strtotime($dates['date_from']) < 1728000) {
            $dates['date_to'] = date('Y-m-d', strtotime($dates['date_from']) + 1728000);
        }
        $_SESSION['date_end'] = $dates['date_to'] . ' 23:59:59';
        return $dates;
    }
}