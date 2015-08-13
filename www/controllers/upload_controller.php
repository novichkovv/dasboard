<?php
/**
 * Created by PhpStorm.
 * User: enovichkov
 * Date: 07.08.2015
 * Time: 13:55
 */
class upload_controller extends controller
{
    public function index()
    {
        if($_FILES) {
            $error = false;
            $mapping_errors = [];
            $type_errors = [];
            foreach ($_FILES as $file) {
                $simpleXLSX = new simpleXLSX_class($file['tmp_name']);
                $array = $simpleXLSX->rows(1);
                if (is_array($array)) {
                    $this->model('work_time')->beginTransaction();
                    foreach ($array as $k => $row) {
                        foreach ($row as $key => $val) {
                            $row[$key] = trim($val);
                        }

                        if($k == 0) {
                            continue;
                        }
                        if (!$user = $this->model('user_mapping')->getByField('user_id', $row[1])) {
                            $mapping_errors[$row[1]] = $row;
                        }
                        $values[$k]['username'] = $user['user_name'];
                        if($row[2] == 'Clock In') {
                            $values[$k]['work_begin'] = date('Y-m-d H:i:s', $simpleXLSX->unixstamp($row[3] + $row[4]));
                        } elseif($row[2] == 'Clock Out') {
                            $values[$k]['id'] = $this->model('work_time')->getByFields(array(
                                'username' => $user['user_name'],
                                'work_end' => '0000-00-00 00:00:00'
                            ))['id'];
                            $values[$k]['work_end'] = date('Y-m-d H:i:s', $simpleXLSX->unixstamp($row[3] + $row[4]));
                        } else {
                            $type_errors[] = array($k + 1, $user['user_name'], $row[2]);
                        }
                        $this->model('work_time')->insert($values[$k]);
                    }
                    if(!$type_errors) {
                        $this->model('work_time')->commitTransaction();
                    } else {
                        $this->model('work_time')->rollbackTransaction();
                    }
                } else {
                    $error = 'File could not be read';
                }
            }

            if ($error) {
                echo json_encode(array('status' => 2, 'error' => $error));
            } elseif ($mapping_errors) {
                echo json_encode(array('status' => 3, 'result' => $mapping_errors));
            } elseif ($type_errors) {
                echo json_encode(array('status' => 4, 'result' => $type_errors));
            } else {
                echo json_encode(array('status' => 1, 'values' => $values));
            }
            exit;
        }
        $this->view('upload' . DS . 'index');
    }
}