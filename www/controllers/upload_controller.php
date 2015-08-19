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
                        if (!$user = $this->model('user_mapping')->getByField('user_name', $row[0])) {
                            $mapping_errors[$row[0]] = $row;
                        }
                        $values[$k]['username'] = $row[0];
                        if($row[2] == 'Clock In') {
                            $values[$k]['work_begin'] = date('Y-m-d H:i:s', $simpleXLSX->unixstamp($row[3] + $row[4]));
                        } elseif($row[2] == 'Clock Out') {
                            $values[$k]['id'] = $this->model('work_time')->getByFields(array(
                                'username' => $row[0],
                                'work_end' => '0000-00-00 00:00:00'
                            ))['id'];
                            $values[$k]['work_end'] = date('Y-m-d H:i:s', $simpleXLSX->unixstamp($row[3] + $row[4]));
                        } else {
                            $type_errors[] = array($k + 1, $user['user_name'], $row[2]);
                        }
                        if($values[$k]['username']) {
                            $this->model('work_time')->insert($values[$k]);
                        }
                    }
                    if(!$type_errors && !$mapping_errors) {
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

    public function index_ajax()
    {
        switch($_REQUEST['action']) {
            case "save_mapping":
                foreach ($_POST['mapping'] as $k => $v) {
                    $_POST['mapping'][$k] = trim($v);
                }
                if(!$_POST['mapping']['user_name'] || !$_POST['mapping']['user_email']) {
                    echo json_encode(array('status' => 2, 'error' => 'Missed Required Fields'));
                } elseif(!$_POST['mapping']['id'] && $this->model('user_mapping')->getByField('user_name', $_POST['mapping']['user_name'])) {
                    echo json_encode(array('status' => 2, 'error' => 'User Name Already Exists'));
                } elseif(!$_POST['mapping']['id'] && $this->model('user_mapping')->getByField('user_email', $_POST['mapping']['user_email'])['id']) {
                    echo json_encode(array('status' => 2, 'error' => 'User Email Already Exists'));
                } else {
                    if($this->model('user_mapping')->insert($_POST['mapping'])) {
                        echo json_encode(array('status' => 1));
                    } else {
                        echo json_encode(array('status' => 2, 'Unexpected Error'));
                    }
                }
                exit;
                break;

        }
    }
}