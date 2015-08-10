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
}