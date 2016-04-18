<?php
/**
 * Created by PhpStorm.
 * User: asus1
 * Date: 18.08.2015
 * Time: 0:15
 */
class mapping_model extends model
{
    public function getNames()
    {
        $stm = $this->pdo->prepare('
            SELECT username FROM asanatt_excel_time GROUP BY username
        ');
        return $this->get_all($stm);
    }
}