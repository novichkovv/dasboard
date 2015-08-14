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

    public function active_projects()
    {

    }


    public function team_member_hours()
    {
        $stm = $this->pdo->prepare('
        SELECT
            username,
            SUM(TIMESTAMPDIFF(SECOND,
                work_begin,
                work_end)) seconds
        FROM
            work_time
        GROUP BY username
        ');
        return $this->get_all($stm);
    }

    public function team_member_table()
    {

    }

    public function utilization()
    {

    }

    public function week()
    {

    }

    public function project_detail()
    {

    }

    public function project_cost()
    {

    }

    public function overall()
    {

    }
}