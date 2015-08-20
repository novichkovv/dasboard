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
    private $date_start;
    private $date_end;
    
    public function index()
    {
        $this->date_start = ($_POST['date_start'] ? $_POST['date_start'] : date('Y-m-d', strtotime(date('Y-m-d') . ' - 15 day'))) . ' 00:00:00';
        $this->date_end = ($_POST['date_end'] ? $_POST['date_end'] : date('Y-m-d')) . ' 23:59:59';
        $permitted_charts = $this->model('charts')->getPermittedChartsList();
        foreach($permitted_charts as $chart) {
            $method_name = $chart['chart_key'];
            if(method_exists($this, $method_name)) {
                $data = $this->model('charts')->$method_name(array('date_start' => $this->date_start, 'date_end' => $this->date_end));
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
        $stats = [];
        foreach($data as $k => $v) {
            $stats['data'][$k] = round($v['hours']);
            $stats['ticks'][$k] = trim($v['project']);
        }
        $this->render('stats', $stats);
    }

    private function team_member_hours($data)
    {
        $stats = [];
        foreach($data as $k => $v) {
            $stats['data'][$k] = round($v['hours']);
            $stats['ticks'][$k] = $v['username'];
        }
        $this->render('stats', $stats);

    }

    private function team_member_table($data)
    {
        $stats = [];
        foreach($data as $k => $v) {
            $stats[$v['username']][$v['date']] = tools_class::formatTime($v['seconds']);
            $dates[$v['date']] = $v['date'];
        }
        foreach($data as $k => $v) {
            foreach($dates as $date) {
                if(!$stats[$v['username']][$date]) {
                    $stats[$v['username']][$date] = '';
                }
            }
        }
        $this->render('stats', $stats);
        $this->render('dates', $dates);
    }

    private function utilization($data)
    {
        $stats = [];
        $i = 0;
        foreach($data as $k => $v) {
            if($v['uploaded']) {
                $stats['data'][$i] = round($v['db'] / $v['uploaded']*100);
            }
            $stats['ticks'][$i] = $k;
            $i ++;
        }
        $this->render('stats', $stats);
    }

    private function week($data)
    {
        $stats = [];
        $i = 0;
        foreach($data as $k => $v) {
            $stats['data'][$i] = $v;
            $stats['ticks'][$i] = $k;
            $i ++;
        }
        $this->render('stats', $stats);
    }

    private function project_detail($data)
    {
        $stats = [];
        foreach($data as $project => $v) {
            $i = 0;
            foreach($v as $task => $val) {
                $stats[$project]['data'][$i] = round($val['hours']);
                $stats[$project]['ticks'][$i] = trim($val['name']);
                $i ++;
            }

        }
        $this->render('stats', $stats);
    }

    private function project_cost($data)
    {
        $stats = [];
        foreach($data as $project => $v) {
            $i = 0;
            foreach($v as $task => $val) {
                $stats[$project]['data'][$i] = round($val['sum'], 2);
                $stats[$project]['ticks'][$i] = trim($val['name']);
                $i ++;
            }
        }
        $this->render('stats', $stats);
    }

    private function overall($data)
    {
        $stats = [];
        $i = 0;
        foreach($data as $val) {
            $stats['data'][$i] = round($val['sum'], 2);
            $stats['ticks'][$i] = trim($val['project']);
            $i ++;
        }

        $this->render('stats', $stats);
    }

    private function randomData()
    {
        $date_start = '2015-06-01';
        $date_end = date('Y-m-d');
        $projects = array("Time Tracker Fix","Time Tracker Fix","Time Tracker Fix","Consider updating your project status","Software ","Cadworx","CRM bug fixes","invoice hcc","","Rolex Time","Shoring takeoff ","I 35 Ellis County ","Update: Jan. - Apr. 2015 projects for Website ","Verify Existing Connection...");
        $tasks = array("Work on Asana","Play with Asana","fix time tracker","Consider updating your project status","Software","Cadworx","CRM bug fixes","invoice hcc","Rolex Time","Shoring takeoff","I 35 Ellis County ","Update: Jan. - Apr. 2015 projects for Website ","Verify Existing Con...");
        $n = 0;
        for($i = strtotime($date_start); $i <= strtotime($date_end); $i += 3600*24) {
            foreach ($this->model('user_mapping')->getAll() as $user) {
                $time = $this->randomTimeRange();
                $start = date('Y-m-d', $i) . ' ' . $time['start'];
                $end = date('Y-m-d', $i) . ' ' . $time['end'];
                for($j = 0; $j < rand(0,2); $j ++) {
                    $row = [];
                    $row['work_begin'] = $start;
                    $row['work_end'] = $end;
                    $tid = $this->model('asanatt_worktime')->insert($row);
                    $row = [];
                    $row['tid'] = $tid;
                    $row['project'] = $projects[rand(0, count($projects) - 1)];
                    $row['name'] = $tasks[rand(0, count($tasks) - 1)];
                    $row['username'] = $user['user_name'];
                    $this->model('asanatt_task')->insert($row);
                }

            }

        }
        exit;
    }
    
    private function randomTimeRange()
    {
        $hour_start = rand(9,17);
        $minute_start = rand(1,59);
        $hour_end = rand($hour_start, 18);
        if($hour_end == $hour_start) {
            $minute_end = rand($minute_start + 1, 59);
        } else {
            $minute_end = rand(1,59);
        }
        $time = [];
        $time['start'] = ($hour_start < 10 ? '0' . $hour_start : $hour_start) . ':' . ($minute_start < 10 ? '0' . $minute_start : $minute_start) . ':00';
        $time['end'] = ($hour_end < 10 ? '0' . $hour_end : $hour_end) . ':' . ($minute_end < 10 ? '0' . $minute_end : $minute_end) . ':00';
        return $time;
    }
    
}