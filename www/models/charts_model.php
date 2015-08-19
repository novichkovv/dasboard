<?php
/**
 * Created by PhpStorm.
 * User: enovichkov
 * Date: 10.08.2015
 * Time: 14:36
 */
class charts_model extends model
{
    public function getPermittedChartsList()
    {
        $stm = $this->pdo->prepare('
            SELECT
                *
            FROM
                charts c
                    JOIN
                charts_user_groups_relations r ON r.chart_id = c.id
            WHERE
                r.user_group_id = :user_group_id
            ORDER BY c.position
        ');
        return $this->get_all($stm, array('user_group_id' => registry::get('user')['user_group_id']));
    }

    public function active_projects($date_range)
    {
        $stm = $this->pdo->prepare('
        SELECT
            project,
            SUM(TIMESTAMPDIFF(HOUR,
                work_begin,
                work_end)) hours
        FROM
            asanatt_task t
                JOIN
            asanatt_worktime w USING (tid)
        WHERE
            work_begin > :date_start
                AND work_end < :date_end
        GROUP BY project
		');
        return $this->get_all($stm, $date_range);
    }


    public function team_member_hours($date_range)
    {
        $stm = $this->pdo->prepare('
        SELECT
            username,
            SUM(TIMESTAMPDIFF(HOUR,
                work_begin,
                work_end)) hours
        FROM
            asanatt_task t
                JOIN
            asanatt_worktime w USING (tid)
        WHERE
            work_begin > :date_start
                AND work_end < :date_end
        GROUP BY username
        ');
        return $this->get_all($stm, $date_range);
    }

    public function team_member_table($date_range)
    {
        $stm = $this->pdo->prepare('
        SELECT
            DATE(work_begin) date,
            username,
            SUM(TIMESTAMPDIFF(SECOND ,
                        work_begin,
                        work_end)) seconds
        FROM
            work_time
        WHERE
            work_begin > :date_start
                AND work_end < :date_end
            GROUP BY DATE(work_begin), username
        ');
        return $this->get_all($stm, $date_range);
    }

    public function utilization($date_range)
    {

    }

    public function week($date_range)
    {

    }

    public function project_detail($date_range)
    {

    }

    public function project_cost($date_range)
    {

    }

    public function overall($date_range)
    {

    }

}