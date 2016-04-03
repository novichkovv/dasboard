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
}