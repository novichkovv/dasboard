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
            foreach ($_FILES as $file) {
                //$array = file_get_contents($file['tmp_name']);
                $simpleXLSX = new simpleXLSX_class($file['tmp_name']);
                $array = $simpleXLSX->rows(1);
                if (is_array($array)) {
                    foreach ($array as $k => $row) {
                        if($k == 0) {
                            continue;
                        }
                        $values[$k]['name'] = $row[0];
                        $values[$k]['id'] = $row[1];
                        $values[$k]['type'] = $row[2];
                        $values[$k]['date'] = date('Y-m-d H:i:s', $simpleXLSX->unixstamp($row[3] + $row[4]));
                        if (!$this->model('user_mapping')->getByField('user_id', $row[1])) {
                            $mapping_errors[$row[1]] = $row;
                        }
                    }
                } else {
                    $error = 'File could not be read';
                }
            }

            if ($error) {
                echo json_encode(array('status' => 2, 'error' => $error));
            } elseif ($mapping_errors) {
                echo json_encode(array('status' => 3, 'result' => $mapping_errors));
            } else {
                echo json_encode(array('status' => 1, 'values' => $values));
            }

            exit;
        }
        $this->view('upload' . DS . 'index');
    }
}