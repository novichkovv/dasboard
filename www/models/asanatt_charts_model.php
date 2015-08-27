<?php
/**
 * Created by PhpStorm.
 * User: enovichkov
 * Date: 10.08.2015
 * Time: 14:36
 */
class asanatt_charts_model extends model
{
    public function getPermittedChartsList($url)
    {
        $stm = $this->pdo->prepare('
            SELECT
                *
            FROM
                asanatt_charts c
                    JOIN
                asanatt_charts_user_groups_relations r ON r.chart_id = c.id
            WHERE
                r.user_group_id = :user_group_id
                    AND
                c.url = :url
            ORDER BY c.position
        ');
        return $this->get_all($stm, array('user_group_id' => registry::get('user')['user_group_id'], 'url' => $url));
    }

    public function getRestChartsList($url)
    {
        $stm = $this->pdo->prepare('
            SELECT
                chart_name,
                url,
                icon,
                color
            FROM
                asanatt_charts c
                    JOIN
                asanatt_charts_user_groups_relations r ON r.chart_id = c.id
            WHERE
                r.user_group_id = :user_group_id
                    AND
                c.url != :url
            ORDER BY c.position
        ');
        return $this->get_all($stm, array('user_group_id' => registry::get('user')['user_group_id'], 'url' => $url));
    }

    public function active_projects($date_range)
    {
        $stm = $this->pdo->prepare('
        SELECT
            project,
            SUM(TIMESTAMPDIFF(SECOND,
                work_begin,
                work_end)) / 3600 hours
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
            SUM(TIMESTAMPDIFF(SECOND,
                work_begin,
                work_end)) / 3600 hours
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
            asanatt_excel_time
        WHERE
            work_begin > :date_start
                AND work_end < :date_end
                AND work_end != "0000-00-00 00:00:00"
            GROUP BY DATE(work_begin), username
        ');
        return $this->get_all($stm, $date_range);
    }

    public function utilization($date_range)
    {
        $hours = [];
        $stm = $this->pdo->prepare('
        SELECT
            username,
            SUM(TIMESTAMPDIFF(SECOND,
                work_begin,
                work_end)) /3600 hours
        FROM
            asanatt_task t
                JOIN
            asanatt_worktime w USING (tid)
        WHERE
            work_begin > :date_start
                AND work_end < :date_end
        GROUP BY username
        ');
        $tmp = $this->get_all($stm, $date_range);
        foreach($tmp as $v) {
            $hours[$v['username']]['db'] = $v['hours'];
        }

        $stm = $this->pdo->prepare('
        SELECT
            username,
            SUM(TIMESTAMPDIFF(SECOND ,
                        work_begin,
                        work_end))/3600 hours
        FROM
            asanatt_excel_time
        WHERE
            work_begin > :date_start
                AND work_end < :date_end
                AND work_end != "0000-00-00 00:00:00"
            GROUP BY username
        ');
        $tmp = $this->get_all($stm, $date_range);
        foreach($tmp as $v) {
            $hours[$v['username']]['uploaded'] = $v['hours'];
        }
        return $hours;
    }

    public function week($date_range)
    {
        $stm = $this->pdo->prepare('
        SELECT
            username,
            SUM(TIMESTAMPDIFF(SECOND,
                work_begin,
                work_end))  seconds
        FROM
            asanatt_task t
                JOIN
            asanatt_worktime w USING (tid)
        WHERE
            work_begin > :date_start
                AND work_end < :date_end
        GROUP BY  username, DATE_FORMAT(work_begin, "%Y-%u")
        ');
        $tmp = $this->get_all($stm, $date_range);

        $hours = [];
        foreach($tmp as $v) {
            if(!$hours[$v['username']]['seconds']) {
                $hours[$v['username']]['seconds'] = 0;
                $hours[$v['username']]['count'] = 0;
            }
            $hours[$v['username']]['seconds'] += $v['seconds'] / 3600 / 40;
            $hours[$v['username']]['count'] += 1;
        }
        foreach($hours as $k => $v) {
            if($v['count']) {
                $hours[$k] = $v['seconds'] /$v['count'] * 100;
            }
        }
        return $hours;
    }

    public function project_detail($date_range, $project = '')
    {
        if(!$project) {
            $project = $this->get_row($this->pdo->prepare('SELECT project FROM asanatt_task LIMIT 1'))['project'];
        }
        $stm = $this->pdo->prepare('
        SELECT
			project,
            name,
            SUM(TIMESTAMPDIFF(SECOND,
                work_begin,
                work_end)) / 3600 hours
        FROM
            asanatt_task t
                JOIN
            asanatt_worktime w USING (tid)
        WHERE
            work_begin > :date_start
                AND work_end < :date_end
                AND project = :project
        GROUP BY name
        ');
        $date_range['project'] = $project;
        $tmp = $this->get_all($stm, $date_range);
        $res = [];
        foreach($tmp as $v) {
            $res[$v['name']] = $v;
        }
        $res['project'] = $project;
        return $res;
    }

    public function project_cost($date_range, $project = '')
    {
        if(!$project) {
            $project = $this->get_row($this->pdo->prepare('SELECT project FROM asanatt_task LIMIT 1'))['project'];
        }
        $stm = $this->pdo->prepare('
        SELECT
			project,
            name,
            TIMESTAMPDIFF(SECOND,
                work_begin,
                work_end) / 3600 hours,
            user_rate
        FROM
            asanatt_task t
                JOIN
            asanatt_worktime w USING (tid)
				join
			asanatt_user_mapping m ON m.user_name = t.username
        WHERE
            work_begin > :date_start
                AND work_end < :date_end
                AND project = :project
        ');
        $date_range['project'] = $project;
        $tmp = $this->get_all($stm, $date_range);
        $res = [];
        foreach($tmp as $v) {
            if(!$res[$v['name']]['sum']) {
                $res[$v['name']]['sum'] = 0;
                $res[$v['name']]['project'] = $v['project'];
                $res[$v['name']]['name'] = $v['name'];
            }
            $res[$v['name']]['sum'] += ($v['hours'] * $v['user_rate']);
        }
        return $res;
    }

    public function overall($date_range)
    {
        $stm = $this->pdo->prepare('
        SELECT
            project,
            TIMESTAMPDIFF(SECOND,
                work_begin,
                work_end) / 3600 * user_rate sum

        FROM
            asanatt_task t
                JOIN
            asanatt_worktime w USING (tid)
				join
			asanatt_user_mapping m ON m.user_name = t.username
        WHERE
            work_begin > :date_start
                AND work_end < :date_end
        ');
        $tmp = $this->get_all($stm, $date_range);
        $res = [];
        foreach($tmp as $v) {
            if(!$res[$v['project']]['sum']) {
                $res[$v['project']]['sum'] = 0;
                $res[$v['project']]['project'] = $v['project'];
            }
            $res[$v['project']]['sum'] += $v['sum'];
        }
        return $res;
    }

    public function getProjectList($date_range)
    {
        $stm = $this->pdo->prepare('
            SELECT project FROM asanatt_task JOIN asanatt_worktime USING (tid) WHERE work_begin >= :date_start AND work_end <= :date_end GROUP BY project
        ');
        return $this->get_all($stm, $date_range);
    }

}