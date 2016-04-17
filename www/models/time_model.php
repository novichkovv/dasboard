<?php
/**
 * Created by PhpStorm.
 * User: asus1
 * Date: 29.03.2016
 * Time: 12:37
 */
class time_model extends model
{
    public function getWorkedTimeNew($task_id, $user_id)
    {
        $stm = $this->pdo->prepare("
           SELECT
                work_begin,
                work_end,
                CONCAT(LPAD(MOD(HOUR(TIMEDIFF(work_begin, work_end)),
                                    24),
                                2,
                                '0'),
                        ':',
                        LPAD(MINUTE(TIMEDIFF(work_begin, work_end)),
                                2,
                                '0')) AS work_duration
            FROM
                asanatt_worktime
            WHERE
                tid = :task_id AND userid = :user_id
            ORDER BY work_begin DESC
        ");
        return $this->get_all($stm, array('task_id' => $task_id, 'user_id' => $user_id));
    }

    public function getWorkedTimeDaily($task_id, $user_id, $week_offset = 0)
    {
        $wd_index = date('w') - 1;
        $date_start = date('Y-m-d 00:00:00', strtotime(date('Y-m-d') . ' - ' . $wd_index . ' day'));
        if($week_offset > 0) {
            $date_start = date('Y-m-d 00:00:00', strtotime(date('Y-m-d') . ' + ' . $week_offset*7 . ' day'));
        }
        if($week_offset < 0) {
            $date_start = date('Y-m-d 00:00:00', strtotime($date_start . ' - ' . abs($week_offset*7) . ' day'));
        }
        $date_end = date('Y-m-d 23:59:59', strtotime($date_start . ' + 6 day'));
        $stm = $this->pdo->prepare("
           SELECT
                DATE (work_begin) date,
                work_begin,
                work_end,
                CONCAT(LPAD(MOD(HOUR(TIMEDIFF(work_begin, work_end)),
                                    24),
                                2,
                                '0'),
                        ':',
                        LPAD(MINUTE(TIMEDIFF(work_begin, work_end)),
                                2,
                                '0')) AS work_duration
            FROM
                asanatt_worktime
            WHERE
                tid = :task_id AND userid = :user_id AND work_begin BETWEEN :date_start AND :date_end
            ORDER BY work_begin DESC
        ");
//        echo $stm->getQuery(array('task_id' => $task_id, 'user_id' => $user_id, 'date_start' => $date_start, 'date_end' => $date_end));
        $tmp = $this->get_all($stm, array('task_id' => $task_id, 'user_id' => $user_id, 'date_start' => $date_start, 'date_end' => $date_end));
        $res = [];

        for($i = strtotime($date_start); $i <= strtotime($date_end); $i += 3600*24) {
            $date = date('Y-m-d', $i);
            $res[$date] = [];
        }
        foreach ($tmp as $k => $v) {
            if (!$res[$v['date']]) {
                $res[$v['date']] = $v;
            } else {
                $arr = explode(':', $v['work_duration']);
                $diff = $arr[0]*60 + $arr[1];
                $work_duration = $res[$v['date']]['work_duration'];
                $res[$v['date']] = $v;
                $res[$v['date']]['work_duration'] = date('H:i', strtotime($v['date'] . ' ' . $work_duration . ':00 + ' . $diff . ' minutes'));
            }
        }
        return $res;

    }

    public function updateTime($time, $tid, $userid, $work_begin)
    {
        $stm = $this->pdo->prepare('
            UPDATE asanatt_worktime SET work_end = :work_end WHERE tid = :tid AND userid = :userid AND work_begin = :work_begin
        ');
        $stm->execute(array(
            'work_end' => $time,
            'tid' => $tid,
            'userid' => $userid,
            'work_begin' => $work_begin
        ));
    }

    public function clearDay($userid, $tid, $date)
    {
        $stm = $this->pdo->prepare('
            DELETE FROM asanatt_worktime WHERE tid = :tid AND userid = :userid AND DATE(work_begin) = :date
        ');
        $stm->execute(array(
            'tid' => $tid,
            'userid' => $userid,
            'date' => $date
        ));
    }
}