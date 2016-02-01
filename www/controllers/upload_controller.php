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
            $date_errors = [];
            $values = [];
            $unclosed = [];
            $tmp = $this->model('asanatt_excel_time')->getByField('work_end', '0000-00-00 00:00:00', true);
            foreach($tmp as $row) {
                $unclosed[$row['username']] = $row;
            }

            foreach ($_FILES as $file) {
                $simpleXLSX = new simpleXLSX_class($file['tmp_name']);
                $array = $simpleXLSX->rows(1);
                if (is_array($array)) {
                    $this->model('asanatt_excel_time')->beginTransaction();
                    $arr = [];
                    foreach ($array as $k => $row) {
                        foreach($row as $key => $val) {
                            $row[$key] = trim($val);
                            $row[5] = $k + 1;
                        }
                        if($k == 0) {
                            continue;
                        }
                        $unix_time = $simpleXLSX->unixstamp($row[3] + $row[4]);
                        $date_time = date('Y-m-d H:i:s', $unix_time);
                        if($unclosed[$row[0]]) {
                            $arr[$row[0]][$unclosed[$row[0]]['work_begin']] = array(
                                $row[0],
                                '',
                                'Clock In',
                                '',
                                '',
                                0,
                                $unclosed[$row[0]]['id']
                            );
                        }
                        if(!$arr[$row[0]][$date_time]) {
                            $arr[$row[0]][$date_time] = $row;
                        } else {
                            $arr[$row[0][date('Y-m-d H:i:s', $unix_time + 1)]];
                        }
                    }
                    foreach ($arr as $user_name => $rows) {
                        ksort($rows);
                        foreach($rows as $date_time => $row) {
                            if (!$user = $this->model('asanatt_user_mapping')->getByField('user_name', $row[0])) {
                                $mapping_errors[$user_name] = $row;
                            }
                            if($row[2] == 'Clock In') {
                                $index = count($values[$user_name]);
                                if($row[6]) {
                                    $index = 0;
                                    $values[$row[0]][$index]['id'] = $row[6];
                                }
                                $values[$user_name][$index]['k'] = $row[5];
                                $values[$user_name][$index]['work_begin'] = $date_time;
                                $values[$user_name][$index]['username'] = $user_name;
                            } elseif($row[2] == 'Clock Out') {
                                $index = count($values[$user_name]) - 1;
                                $values[$row[0]][$index]['work_end'] = $date_time;
                                $values[$user_name][$index]['username'] = $user_name;
                            } else {
                                $type_errors[] = array($row['k'], $user_name, $row[2]);
                            }
                        }
                    }
                    if ($values) {
                        foreach ($values as $v) {
                            foreach ($v as $row) {
//                                if(!$row['work_begin'] || date('Y-m-d', strtotime($row['work_begin']) == '1970-01-01' || $row['work_begin']) == '000-00-00 00:00:00'
//                                || !$row['work_end'] || date('Y-m-d', strtotime($row['work_end']) == '1970-01-01' || $row['work_end']) == '000-00-00 00:00:00') {
//                                    $date_errors[] = array($row['k'], $row['username'], 2);
//                                } else
                                if($row['work_end'] && $row['work_end'] <= $row['work_begin']) {
                                    //$date_errors[] = array($row['k'], $row['username'], 2);
                                }
                                unset($row['k']);
                                $this->model('asanatt_excel_time')->insert($row);
                            }
                        }

                    }
                    if(!$type_errors && !$mapping_errors && !$date_errors) {
                        $this->model('asanatt_excel_time')->commitTransaction();
                    } else {
                        $this->model('asanatt_excel_time')->rollbackTransaction();
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
            } elseif ($date_errors) {
                echo json_encode(array('status' => 5, 'result' => $date_errors));
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
                } elseif(!$_POST['mapping']['id'] && $this->model('asanatt_user_mapping')->getByField('user_name', $_POST['mapping']['user_name'])) {
                    echo json_encode(array('status' => 2, 'error' => 'User Name Already Exists'));
                } elseif(!$_POST['mapping']['id'] && $this->model('asanatt_user_mapping')->getByField('user_email', $_POST['mapping']['user_email'])['id']) {
                    echo json_encode(array('status' => 2, 'error' => 'User Email Already Exists'));
                } else {
                    if($this->model('asanatt_user_mapping')->insert($_POST['mapping'])) {
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