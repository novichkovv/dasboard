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
            *
        FROM
            asanatt_excel_time
        WHERE
            HOUR(work_begin) > ' . registry::get('config')['clock_in_end'] . '
                AND
            work_begin BETWEEN :date_from AND :date_to
        GROUP BY DAY(work_begin);
        ');
        return $this->get_all($stm, $dates);
    }

    public function getAbsentEmployees($dates)
    {
        $users = [];
        foreach ($this->model('asanatt_user_mapping')->getAll() as $user) {
            $users[$user['id']] = $user;
        }

        $stm = $this->pdo->prepare('
        SELECT
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
            $worked[$v['date']][] = $v['user_id'];
        }
        $absent = [];
        for($i = strtotime($dates['date_from']); $i <= strtotime($dates['date_to']); $i = $i + 3600*24) {
            foreach ($users as $user_id => $user) {
                if($worked[date('Y-m-d', $i)]) {
                    if(!in_array($user_id, $worked[date('Y-m-d', $i)])) {
                        $absent[date('Y-m-d', $i)][$user_id] = $user;
                    }
                }
            }

        }
        return $absent;
    }

    public function getEarlyFinished($dates)
    {
        $stm = $this->pdo->prepare('
        SELECT
            *
        FROM
            asanatt_excel_time
        WHERE
            HOUR(work_begin) < ' . registry::get('config')['clock_out_start'] . '
                AND
            work_begin BETWEEN :date_from AND :date_to
        GROUP BY DAY(work_begin);
        ');
        return $this->get_all($stm, $dates);
    }

    public function getLessWorked($dates)
    {
        $stm = $this->pdo->prepare('
        SELECT
            *
        FROM

        ');
    }
}