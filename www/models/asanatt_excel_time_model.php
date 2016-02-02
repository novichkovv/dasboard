<?php
/**
 * Created by PhpStorm.
 * User: asus1
 * Date: 27.01.2016
 * Time: 20:44
 */ 
class asanatt_excel_time_model extends model 
{
    public function getLateEmployees($dates)
    {
        $stm = $this->pdo->prepare('
        SELECT
            DATE(t.work_begin) date,
            HOUR(TIMEDIFF(DATE_FORMAT(work_begin, "%Y-%m-%d ' . registry::get('config')['clock_in_end'] . ':00:00"),
                        work_begin)) + MINUTE(TIMEDIFF(DATE_FORMAT(work_begin, "%Y-%m-%d ' . registry::get('config')['clock_in_end'] . ':00:00"),
                        work_begin)) / 60 value,
            u.id user_id
        FROM
            asanatt_excel_time t
                JOIN
            asanatt_user_mapping u ON u.user_name = t.username
        WHERE
            TIME(work_begin) > "' . (registry::get('config')['clock_in_end'] >= 10 ? registry::get('config')['clock_in_end'] : '0' . (int) registry::get('config')['clock_in_end']) . ':00:00"
                AND
            work_begin BETWEEN :date_from AND :date_to
        ');
        $tmp = $this->get_all($stm, $dates);
        $late = [];
        foreach ($tmp as $v) {
            $late[$v['date']][$v['user_id']] = $v;
        }
        return $late;
    }

    public function getAbsentEmployees($dates)
    {
        $users = [];
        foreach ($this->model('asanatt_user_mapping')->getAll() as $user) {
            $users[$user['id']] = $user;
        }

        $stm = $this->pdo->prepare('
        SELECT
            work_begin,
            work_end,
            DATE(t.work_begin) date,
            u.id user_id
        FROM
            asanatt_excel_time t
                JOIN
            asanatt_user_mapping u ON u.user_name = t.username
        WHERE
            work_begin BETWEEN :date_from AND :date_to
        ');
        $tmp = $this->get_all($stm, $dates);
        $worked = [];
        foreach ($tmp as $v) {
            $worked[$v['date']][$v['user_id']] = $v;
        }
        $res = [];
        for($i = strtotime($dates['date_from']); $i <= strtotime($dates['date_to']); $i = $i + 3600*24) {
            foreach ($users as $user_id => $user) {
                if($worked[date('Y-m-d', $i)]) {
                    if(!array_key_exists($user_id, $worked[date('Y-m-d', $i)])) {
                        $res['absent'][date('Y-m-d', $i)][$user_id] = $user;
                    } else {
                        $res['present'][date('Y-m-d', $i)][$user_id] = $worked[date('Y-m-d', $i)][$user_id];
                    }
                }
            }
        }
        return $res;
    }

    public function getEarlyFinished($dates)
    {
        $stm = $this->pdo->prepare('
        SELECT
            DATE(t.work_end) date,
            t.work_end,
            HOUR(TIMEDIFF(DATE_FORMAT(work_end, "%Y-%m-%d ' . registry::get('config')['clock_out_start'] . ':00:00"),
                        work_end)) + MINUTE(TIMEDIFF(DATE_FORMAT(work_begin, "%Y-%m-%d ' . registry::get('config')['clock_out_start'] . ':00:00"),
                        work_end)) / 60 value,
            u.id user_id
        FROM
            asanatt_excel_time t
                JOIN
            asanatt_user_mapping u ON u.user_name = t.username
        WHERE
            TIME(work_end) < "' . (registry::get('config')['clock_out_start'] >= 10 ? registry::get('config')['clock_out_start'] : '0' . (int) registry::get('config')['clock_out_start']) . ':00:00"
                AND
            DATE(work_begin) = DATE(work_end)
                AND
            work_begin BETWEEN :date_from AND :date_to
        ');
        $tmp = $this->get_all($stm, $dates);
        $early = [];
        foreach ($tmp as $v) {
            $early[$v['date']][$v['user_id']] = $v;
        }
        return $early;
    }

    public function getLessWorked($dates)
    {
        $stm = $this->pdo->prepare('
        SELECT
            DATE(t.work_end) date,
            u.id user_id,
            ' . registry::get('config')['day_length'] . ' -
            (HOUR(TIMEDIFF(work_end, work_begin)) +
            MINUTE(TIMEDIFF(work_end, work_begin))/60) value
        FROM
            asanatt_excel_time t
                JOIN
            asanatt_user_mapping u ON u.user_name = t.username
        WHERE
            work_begin BETWEEN :date_from AND :date_to
                AND TIMEDIFF(work_end, work_begin) < "' . (registry::get('config')['day_length'] >= 10 ? registry::get('config')['day_length'] : '0' . (int) registry::get('config')['day_length']) . ':00:00"
        ');
        $tmp = $this->get_all($stm, $dates);
        $res = [];
        foreach ($tmp as $v) {
            $res[$v['date']][$v['user_id']] = $v;
        }
        return $res;
    }

    public function getOvertime($dates)
    {
        $stm = $this->pdo->prepare('
        SELECT * FROM dashboard.asanatt_overtime WHERE work_date BETWEEN :date_from AND :date_to
        ');
        $approved = [];
        foreach ($this->get_all($stm, $dates) as $v) {
            $approved[$v['work_date']][$v['user_id']] = $v['overtime_approved'];
        }
        $stm = $this->pdo->prepare('
        SELECT
            DATE(t.work_end) date,
            u.id user_id,
            (HOUR(TIMEDIFF(work_end, work_begin)) +
            MINUTE(TIMEDIFF(work_end, work_begin))/60) - ' . registry::get('config')['day_length'] . ' value
        FROM
            asanatt_excel_time t
                JOIN
            asanatt_user_mapping u ON u.user_name = t.username
        WHERE
            work_begin BETWEEN :date_from AND :date_to
                AND TIMEDIFF(work_end, work_begin) > "' . (registry::get('config')['day_length'] >= 10 ? registry::get('config')['day_length'] : '0' . (int) registry::get('config')['day_length']) . ':00:00"
        ');
        $tmp = $this->get_all($stm, $dates);
        //print_r($tmp);
        $res = [];
        foreach ($tmp as $v) {
            if($approved[$v['date']][$v['user_id']] > $v['value']) {
                $v['value'] = $approved[$v['date']][$v['user_id']];
                $res[$v['date']][$v['user_id']] = $v;
            } elseif($approved[$v['date']][$v['user_id']]) {
                $v['value'] = $approved[$v['date']][$v['user_id']];
                $res[$v['date']][$v['user_id']] = $v;
            }
        }
        return $res;
    }
}