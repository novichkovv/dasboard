<?php
/**
 * Created by PhpStorm.
 * User: asus1
 * Date: 29.03.2016
 * Time: 10:35
 */
class tracker_controller extends controller
{
    public function index()
    {
        //unset($_SESSION['asana']);
        if(!isset($_SESSION['asana']['workspace_id'])) {
            $this->workspaces();
        } else {
            $this->render('tasks_template', $this->tasks($_SESSION['asana']['workspace_id']));
        }
        $this->view('tracker' . DS . 'index');
    }

    public function index_ajax()
    {
        switch ($_REQUEST['action']) {
            case "set_workspace":
                $_SESSION['asana']['workspace_id'] = $_POST['workspace_id'];
                $_SESSION['asana']['workspace_name'] = $_POST['workspace_name'];
                echo $this->tasks($_POST['workspace_id']);
                exit;
                break;

            case "update_workspace":
                unset($_SESSION['asana']['tasks']);
                echo $this->tasks($_SESSION['asana']['workspace_id']);
                exit;
                break;

            case "change_workspace":
                unset($_SESSION['asana']);
                $this->workspaces();
                $this->view_only('tracker' . DS . 'workspaces');
                exit;
                break;

            case "register_time":
                if(empty($_POST['time']) || empty($_POST['id'])) {
                    echo json_encode(array('status' => 2));
                    exit;
                }
                $res = array('status' => 1);
                $time = TRACKING_FREQUENCY;
                if(!empty($_COOKIE['asana_cached_time_' . $_POST['id']])) {
                    $time += TRACKING_FREQUENCY * $_COOKIE['asana_cached_time_' . $_POST['id']];
                    setcookie('asana_cached_time_' . $_POST['id'], 0, time() - 24*3600, '/', '.' . substr('http://', '', SITE_DIR));
                }
                if(!empty($_POST['first'])) {
                    $row = [];
                    $row['tid'] = $_POST['id'];
                    $row['userid'] = registry::get('user')['asana_id'];
                    $date = date('Y-m-d H:i:s');
                    $row['work_begin'] = $date;
                    $row['work_end'] = $date;
                    if($time > TRACKING_FREQUENCY) {
                        $time -= TRACKING_FREQUENCY;
                        $row['work_begin'] = date('Y-m-d H:i:s', strtotime($date . ' - ' . $time . ' second'));
                    }
                    $res['work_begin'] = $row['work_begin'];
                    $this->model('asanatt_worktime')->insert($row);
                } else {
                    if ($row = $this->model('asanatt_worktime')->getByFields(array(
                        'tid' => $_POST['id'],
                        'userid' => registry::get('user')['asana_id'],
                        'work_begin' => $_POST['work_begin']
                    ))) {
                        $work_end = date('Y-m-d H:i:s', strtotime($row['work_end'] . ' + ' . $time . ' second'));
                        $this->model('time')->updateTime($work_end, $_POST['id'], registry::get('user')['asana_id'], $_POST['work_begin']);
                    }
                }

                $worked_time = $this->getWorkedTime($row['tid'], registry::get('user')['asana_id']);
                $res['time'] = $worked_time;
                echo json_encode($res);
                exit;
                break;

            case "get_edit_task_form":
                $tasks = $this->model('asanatt_worktime')->getByFields(array(
                    'tid' => $_POST['tid'],
                    'userid' => registry::get('user')['asana_id']
                ), true);
                foreach ($tasks as $k => $task) {
                    $start = new DateTime($task['work_begin']);
                    $end = new DateTime($task['work_end']);
                    $tasks[$k]['time'] = $start->diff($end)->format('%H:%I');
                }
                $this->render('tasks', $tasks);
                $this->render('tid', $_POST['tid']);
                $template = $this->fetch('tracker' . DS . 'edit_task_form');
                echo json_encode(array('status' => 1, 'template' => $template));
                exit;
                break;

            case "edit_task":
                $time_arr = explode(':', $_POST['time']);
                $time = $time_arr[0]*60 + $time_arr[1];
                $work_end = date('Y-m-d H:i:s', strtotime($_POST['work_begin'] . ' + ' . $time . ' minute'));
                $this->model('time')->updateTime($work_end, $_POST['tid'], registry::get('user')['asana_id'], $_POST['work_begin']);
                $worked_time = $this->getWorkedTime($_POST['tid'], registry::get('user')['asana_id']);
                echo json_encode(array('status' => 1, 'worked_time' => $worked_time, 'tid' => $_POST['tid']));
                exit;
                break;

            case "delete_task":
                $this->model('asanatt_worktime')->deleteByFields(array(
                    'tid' => $_POST['tid'],
                    'work_begin' => $_POST['work_begin'],
                    'userid' => registry::get('user')['asana_id']
                ));
                $worked_time = $this->getWorkedTime($_POST['tid'], registry::get('user')['asana_id']);
                echo json_encode(array('status' => 1, 'worked_time' => $worked_time, 'tid' => $_POST['tid']));
                exit;
                break;

            case "add_new_task":
                if($_POST['time'] != '00:00') {
                    $work_begin = $_POST['new_time_date'] . ' ' . $_POST['new_time_start'] . ':00';
                    $time_arr = explode(':', $_POST['new_time']);
                    $time = $time_arr[0]*60 + $time_arr[1];
                    $work_end = date('Y-m-d H:i:s', strtotime($work_begin . " + $time minute"));
                    $row = [];
                    $row['tid'] = $_POST['tid'];
                    $row['userid'] = registry::get('user')['asana_id'];
                    $row['work_begin'] = $work_begin;
                    $row['work_end'] = $work_end;
                    $this->model('asanatt_worktime')->insert($row);
                }
                $worked_time = $this->getWorkedTime($_POST['tid'], registry::get('user')['asana_id']);
                echo json_encode(array('status' => 1, 'worked_time' => $worked_time, 'tid' => $_POST['tid']));
                exit;
                break;

            case "save_manual_time":
                $worked_time = [];
                foreach ($_POST['manual'] as $tid => $v) {
                    foreach ($v as $date => $value) {
                        if($value['new'] != $value['current']) {
                            $n = explode(':', $value['new']);
                            $new = $n[0]*60 + $n[1];
                            $this->model('time')->clearDay(registry::get('user')['asana_id'], $tid, $date);
                            if($new) {
                                $sign = $new > 0 ? '+' : '-';
                                $this->model('asanatt_worktime')->insert(array(
                                    'userid' => registry::get('user')['asana_id'],
                                    'tid' => $tid,
                                    'work_begin' => $date . ' 09:00:00',
                                    'work_end' => date('Y-m-d H:i:s', strtotime($date . ' 09:00:00' . ' ' . $sign . ' ' . $new . ' minutes'))
                                ));
                            }
                        }
                    }
                    $worked_time[$tid] = $this->getWorkedTime($tid, registry::get('user')['asana_id']);
                }
                echo json_encode(array('status' => 1, 'worked_time' => $worked_time));
                exit;
                break;

            case "get_manual_form":
                $tasks = $_SESSION['asana']['tasks'];
                foreach ($tasks['data'] as $k => $task) {
                    $id = number_format($task['projects'][0]['id'], 0, '.', '');
                    $tasks['data'][$k]['projects'][0]['id'] = $id;
                    $task['projects'][0]['id'] = $id;
                    $projects[$id] = $task['projects'][0];
                    $tasks['data'][$k]['worked_time'] = $this->getWorkedTime($task['id'],registry::get('user')['asana_id']);
                    $tasks['daily'][$k] = $this->model('time')->getWorkedTimeDaily($task['id'],registry::get('user')['asana_id'], $_POST['week_offset']);
                    if(!empty($_GET['project'])) {
                        if($task['projects'][0]['id'] != $_GET['project']) {
                            unset($tasks['data'][$k]);
                            unset($tasks['daily'][$k]);
                        }
                    }
                }
                $this->render('week_offset', $_POST['week_offset']);
                $this->render('tasks', $tasks);
                $template = $this->fetch('tracker' . DS . 'manual_form');
                echo json_encode(array('status' => 1, 'template' => $template));
                exit;
                break;
        }
    }

    private function workspaces()
    {
        if(!empty($_SESSION['asana']['workspaces'])) {
            $workspaces = $_SESSION['asana']['workspaces'];
        } else {
            $workspaces = $this->api()->getWorkspaces();
            $_SESSION['asana']['workspaces'] = $workspaces;
        }
        $this->render('workspaces', $workspaces);
    }

    private function tasks($workspace_id)
    {
        $this->render('week_offset', 0);
        $this->render('workspace_name', $_SESSION['asana']['workspace_name']);
        if(!$_SESSION['asana']['tasks']) {
            $tasks = $this->api()->getTasks($workspace_id, registry::get('user')['asana_id']);
            $_SESSION['asana']['tasks'] = $tasks;
        } else {
            $tasks = $_SESSION['asana']['tasks'];
        }
        $projects = [];
        foreach ($tasks['data'] as $k => $task) {
            $id = number_format($task['projects'][0]['id'], 0, '.', '');
            $tasks['data'][$k]['projects'][0]['id'] = $id;
            $task['projects'][0]['id'] = $id;
            $projects[$id] = $task['projects'][0];
            $tasks['data'][$k]['worked_time'] = $this->getWorkedTime($task['id'],registry::get('user')['asana_id']);
            $tasks['daily'][$k] = $this->model('time')->getWorkedTimeDaily($task['id'],registry::get('user')['asana_id']);

            $ex_task = $this->model('asanatt_task')->getByFields(array( 'tid' =>  $task['id'], 'userid' => registry::get('user')['asana_id']));
            if(!$ex_task || !$ex_task['userid']) {
                $ex_task['tid'] = $task['id'];
                $ex_task['userid'] = registry::get('user')['asana_id'];
                $ex_task['project'] = isset($task['projects'][0]['name']) ? $task['projects'][0]['name'] : '';
                $ex_task['name'] = $task['name'];
                $ex_task['username'] = registry::get('user')['email'];
                $play_date = $task['created_at'] ? substr($task['created_at'], 0, 10) : date('Y-m-d');
                $ex_task['play_date'] = $play_date;
                $this->model('asanatt_task')->insert($ex_task);
            } elseif($ex_task['project'] != $task['projects'][0]['name'] || $ex_task['name'] != $task['name']) {
                $ex_task['project'] = isset($task['projects'][0]['name']) ? $task['projects'][0]['name'] : '';
                $ex_task['name'] = $task['name'];
                $this->model('asanatt_task')->insert($ex_task);
            }
            if(!empty($_GET['project'])) {
                if($task['projects'][0]['id'] != $_GET['project']) {
                    unset($tasks['data'][$k]);
                    unset($tasks['daily'][$k]);
                }
            }
        }
        $this->render('projects', $projects);
        $this->render('tasks', $tasks);
        $template = $this->fetch('tracker' . DS . 'tasks');
        return $template;
    }

    private function getWorkedTime($taskId, $userId)
    {
        $result = $this->model('time')->getWorkedTimeNew($taskId, $userId);

        $sum = new DateTime('00:00');
        $old = clone $sum;
        foreach ($result as $row) {
            $ds = new DateTime($row["work_begin"]);
            $de = new DateTime($row["work_end"]);
            $sum->add($ds->diff($de));
        }

        $days = intval($old->diff($sum)->format("%d"));
        $hours = intval($old->diff($sum)->format("%H"));
        if($days > 0 ){
            $hours = $hours + $days * 24;
        }


        if(strlen($hours) <= 1){
            $hours = "0". $hours;
        }
        $returnVal = $hours . ":" . $old->diff($sum)->format("%I:%S");
        return $returnVal;
    }
}