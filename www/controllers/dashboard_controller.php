<?php
/**
 * Created by PhpStorm.
 * User: asus1
 * Date: 25.05.2015
 * Time: 1:19
 */
class dashboard_controller extends controller
{
    private $template = '';
    private $date_start;
    private $date_end;
    
    public function index()
    {
        $this->render('links', $this->model('asanatt_charts')->getRestChartsList(__FUNCTION__));
        $this->getCharts(__FUNCTION__);
    }

    public function members_table()
    {
        $this->render('links', $this->model('asanatt_charts')->getRestChartsList(__FUNCTION__));
        $this->getCharts(__FUNCTION__);
    }

    public function office_utilization()
    {
        $this->render('links', $this->model('asanatt_charts')->getRestChartsList(__FUNCTION__));
        $this->getCharts(__FUNCTION__);
    }

    public function week_performance()
    {
        $this->render('links', $this->model('asanatt_charts')->getRestChartsList(__FUNCTION__));
        $this->getCharts(__FUNCTION__);
    }

    public function detail()
    {
        $this->render('links', $this->model('asanatt_charts')->getRestChartsList(__FUNCTION__));
        $this->getCharts(__FUNCTION__);
    }

    public function cost()
    {
        $this->render('links', $this->model('asanatt_charts')->getRestChartsList(__FUNCTION__));
        $this->getCharts(__FUNCTION__);
    }

    public function overall_cost()
    {
        $this->render('links', $this->model('asanatt_charts')->getRestChartsList(__FUNCTION__));
        $this->getCharts(__FUNCTION__);
    }

    private function getCharts($url)
    {
        $this->date_start = ($_POST['date_start'] ? $_POST['date_start'] : ( $_SESSION['date_start'] ? $_SESSION['date_start'] : date('Y-m-d', strtotime(date('Y-m-d') . ' - 15 day'))) . ' 00:00:00');
        $this->date_end = ($_POST['date_end'] ? $_POST['date_end'] : ( $_SESSION['date_end'] ? $_SESSION['date_end'] : date('Y-m-d')) . ' 23:59:59');
        $_SESSION['date_start'] = $this->date_start;
        $_SESSION['date_end'] = $this->date_end;
        $permitted_charts = $this->model('asanatt_charts')->getPermittedChartsList($url);
        foreach($permitted_charts as $chart) {
            $method_name = $chart['chart_key'];
            if(method_exists($this, $method_name)) {
                $data = $this->model('asanatt_charts')->$method_name(array('date_start' => $this->date_start, 'date_end' => $this->date_end));
                $this->$method_name($data);
                $this->template .= $this->fetch('charts' . DS . $method_name);
            } else {
                $this->writeLog('ERRORS', 'WRONG chart method "' . $method_name . '" called! ');
            }
        }
        $this->render('template', $this->template);
        $this->view('index');
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
        foreach($stats as $k => $v) {
            foreach($dates as $date) {
                if(!$stats[$k][$date]) {
                    $stats[$k][$date] = ' ';
                }
            }
        }
        foreach($stats as $k => $v) {
            ksort($v);
            $stats[$k] = $v;
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
        $i = 0;
        $this->render('active_project', $data['project']);
        unset($data['project']);
        foreach($data as $project => $v) {
                $stats['data'][$i] = round($v['hours']);
                $stats['ticks'][$i] = trim($v['name']);
                $i ++;
        }
        $this->render('projects', $this->model('asanatt_charts')->getProjectList(array('date_start' => $this->date_start, 'date_end' => $this->date_end)));
        $this->render('stats', $stats);
    }

    public function detail_ajax()
    {
        switch ($_REQUEST['action']) {
            case "get_graph_data":
                $date_range = array('date_start' => $_POST['date_start'], 'date_end' => $_POST['date_end']);
                $data = $this->model('asanatt_charts')->project_detail($date_range, $_POST['project']);
                $stats = [];
                $i = 0;
                unset($data['project']);
                foreach($data as $task => $v) {
                    $stats['data'][$i] = array(round($v['hours']), $i);
                    $stats['ticks'][$i] = array($i, trim($v['name']));
                    $i ++;
                }
                $stats['status'] = 1;
                echo json_encode($stats);
                exit;
                break;
        }
    }

    private function project_cost($data)
    {
        $this->render('active_project', $data['project']);
        unset($data['project']);
        $stats = [];
        $i = 0;
        foreach($data as $task => $v) {
            $stats['data'][$i] = round($v['sum'], 2);
            $stats['ticks'][$i] = trim($v['name']);
            $i ++;
        }
        $this->render('projects', $this->model('asanatt_charts')->getProjectList(array('date_start' => $this->date_start, 'date_end' => $this->date_end)));
        $this->render('stats', $stats);
    }

    public function cost_ajax()
    {
        switch ($_REQUEST['action']) {
            case "get_graph_data":
                $date_range = array('date_start' => $_POST['date_start'], 'date_end' => $_POST['date_end']);
                $data = $this->model('asanatt_charts')->project_cost($date_range, $_POST['project']);
                $stats = [];
                $i = 0;
                unset($data['project']);
                foreach($data as $task => $v) {
                    $stats['data'][$i] = array(round($v['sum'], 2), $i);
                    $stats['ticks'][$i] = array($i, trim($v['name']));
                    $i ++;
                }
                $stats['status'] = 1;
                echo json_encode($stats);
                exit;
                break;
        }
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
            foreach ($this->model('asanatt_user_mapping')->getAll() as $user) {
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