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
        if(!isset($_SESSION['asana']['workspace_id'])) {
            $this->workspaces();
        } else {
            $this->tasks($_SESSION['asana']['workspace_id']);
        }
        $this->view('tracker' . DS . 'index');
        //session_destroy();
    }

    public function index_ajax()
    {
        switch ($_REQUEST['action']) {
            case "set_workspace":
                $_SESSION['asana']['workspace_id'] = $_POST['workspace_id'];
                $_SESSION['asana']['workspace_name'] = $_POST['workspace_name'];
                $this->tasks($_POST['workspace_id']);
                $this->view_only('tracker' . DS . 'tasks');
                exit;
                break;

            case "register_time":
                if(empty($_POST['time']) || empty($_POST['id'])) {
                    echo json_encode(array('status' => 2));
                    exit;
                }
                $res = array('status' => 1);
                $time = TRACKING_FREQUENCY;
                if(!empty($_COOKIE['asana_cached_time'])) {
                    $time += TRACKING_FREQUENCY * $_COOKIE['asana_cached_time'];
                    setcookie('asana_cached_time', 0, time());
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
        $this->render('workspace_name', $_SESSION['asana']['workspace_name']);
        if(!$_SESSION['asana']['tasks']) {
            $tasks = $this->api()->getTasks($workspace_id, registry::get('user')['asana_id']);
            $_SESSION['asana']['tasks'] = $tasks;
        } else {
            $tasks = $_SESSION['asana']['tasks'];
        }
        foreach ($tasks['data'] as $k => $task) {
            $tasks['data'][$k]['worked_time'] = $this->getWorkedTime($task['id'],registry::get('user')['asana_id']);
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
            }
        }

        $this->render('tasks', $tasks);
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