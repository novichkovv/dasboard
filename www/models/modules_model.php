<?php
/**
 * Created by PhpStorm.
 * User: asus1
 * Date: 23.07.2015
 * Time: 1:26
 */
class modules_model extends model
{
    public function getGroupModel()
    {
        return $this->get_all_loc(
            $this->pdo->prepare('
          SELECT
              *,
              l.locale_value
          FROM
              modules_user_groups_relations mr
                JOIN
              modules m ON m.id = mr.module_id
                LEFT JOIN locale l ON l.locale_key = m.module_name AND l.locale_table = "modules" AND l.locale_language = "' . registry::get('language') . '"
          WHERE
              mr.user_group_id = :id
        '),
            'module_name',
            array('id' => registry::get('user')['user_group_id']));
    }

    public function savePositions($positions)
    {
        $stm = $this->pdo->prepare('
        UPDATE modules_user_groups_relations SET x_position = :x_position, y_position = :y_position WHERE module_id = :module_id AND user_group_id = :user_group_id
        ');
        foreach ($positions as $module_id => $data) {
            $data['module_id'] = $module_id;
            $data['user_group_id'] = registry::get('user')['user_group_id'];
            $stm->execute($data);
        }
    }


}