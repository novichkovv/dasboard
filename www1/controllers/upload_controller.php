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
                // print_r(tools_class::readXLS($file['tmp_name']));
//                exit;
//                $simpleXLSX = new simpleXLSX_class($file['tmp_name']);
                //$array = $simpleXLSX->rows(1);
                $sheet = tools_class::readXLS($file['tmp_name'])[0];
                if (is_array($sheet)) {
                    $this->model('asanatt_excel_time')->beginTransaction();
                    $arr = [];
                    foreach ($sheet as $k => $row) {
                        foreach($row as $key => $val) {
                            $row[$key] = trim($val);
                            $row[5] = $k + 1;
                        }
                        $user_name = $row[0];
                        if($k == 0) {
                            continue;
                        }
                        $exp_date = explode('-', $row[3]);
                        $date = '20' . $exp_date[2] . '-' . $exp_date[0] . '-' . $exp_date[1];
                        $date_time = date('Y-m-d H:i:s', strtotime($date . ' ' . $row[4]));
//                        $exp = explode(' ', $row[4]);

                        //$offset = (int) gmdate( 'h', abs( date( 'Z' ) ) );
                        //$unix_time = $simpleXLSX->unixstamp($row[3] + $row[4]);

                        //$date_time = date('Y-m-d H:i:s', $unix_time);
                        //$date_time = date('Y-m-d H:i:s', strtotime($date_time . ' + ' . $offset . ' hour'));
                        //$date_time = date('Y-m-d H:i:s', $unix_time + $offset*3600);
                        //if($user_name == 'Nicdao, Gladys') echo date('Y-m-d H:i:s', strtotime($date . ' ' . $row[4])) . "\n";
//                        if(date('s', strtotime($date_time)) == '59') {
////                            $date_time = date('Y-m-d H:i:s', $unix_time + ($offset + 1)*3600 + 1);
////                            echo $date_time . "\n";
//                        }
//                        if($user_name == 'Bairan, Christian') {
//                            //print_r($row);
//                        //echo $unix_time;
//                            //echo date('Y-m-d H:i:s', $unix_time . ' + ' . $offset . ' hour') . "\n";
//                            echo date('Y-m-d H:i:s', $this->ExcelToPHP($row[3] + $row[4])) . "=\n";
//                            echo date('Y-m-d H:i:s', $unix_time) . "-2\n";
//                            echo $date_time . "-3\n";
////                            exit;
//                        }
                        if($unclosed[$user_name]) {
                            $arr[$user_name][$unclosed[$user_name]['work_begin']] = array(
                                $user_name,
                                '',
                                'Clock In',
                                '',
                                '',
                                0,
                                $unclosed[$user_name]['id']
                            );
                        }
                        if(!$arr[$user_name][$date_time]) {
                            $arr[$user_name][$date_time] = $row;
                        } else {
                            $arr[$user_name][date('Y-m-d H:i:s', strtotime($date_time . ' + 1 minute'))] = $row;
                        }
                    }
                    foreach ($arr as $user_name => $rows) {
                        ksort($rows);
                        foreach($rows as $date_time => $row) {
                            if (!$user = $this->model('asanatt_user_mapping')->getByField('user_name', $user_name)) {
                                $mapping_errors[$user_name] = $row;
                            }
                            if($row[2] == 'Clock In') {
                                $index = count($values[$user_name]);
                                if($row[6]) {
                                    $index = 0;
                                    $values[$user_name][$index]['id'] = $row[6];
                                }
                                $values[$user_name][$index]['k'] = $row[5];
                                $values[$user_name][$index]['work_begin'] = $date_time;
                                $values[$user_name][$index]['username'] = $user_name;
                            } elseif($row[2] == 'Clock Out') {
                                $index = count($values[$user_name]) - 1;
                                $values[$user_name][$index]['work_end'] = $date_time;
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
    function ExcelToPHP($dateValue = 0, $ExcelBaseDate = 1900) {
        if ($ExcelBaseDate == 1900) {
            $myExcelBaseDate = 25569;
            //    Adjust for the spurious 29-Feb-1900 (Day 60)
            if ($dateValue < 60) {
                --$myExcelBaseDate;
            }
        } else {
            $myExcelBaseDate = 24107;
        }

        // Perform conversion
        if ($dateValue >= 1) {
            $utcDays = $dateValue - $myExcelBaseDate;
            $returnValue = round($utcDays * 86400);
            if (($returnValue <= PHP_INT_MAX) && ($returnValue >= -PHP_INT_MAX)) {
                $returnValue = (integer) $returnValue;
            }
        } else {
            $hours = round($dateValue * 24);
            $mins = round($dateValue * 1440) - round($hours * 60);
            $secs = round($dateValue * 86400) - round($hours * 3600) - round($mins * 60);
            $returnValue = (integer) gmmktime($hours, $mins, $secs);
        }

        // Return
        return $returnValue;
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