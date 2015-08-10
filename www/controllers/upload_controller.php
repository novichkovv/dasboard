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
            foreach ($_FILES as $file) {
                //$array = file_get_contents($file['tmp_name']);
                $simpleXLSX = new simpleXLSX_class($file['tmp_name']);
                $array = $simpleXLSX->rows(1);
                if(is_array($array)) {
                    foreach ($array as $k => $row) {
                        $values[$k]['name'] = $row[0];
                        $values[$k]['id'] = $row[1];
                        $values[$k]['type'] = $row[3];
                        $values[$k]['date'] =  date('Y-m-d', ($row[4])*86400) . ' ' . date('H:i:s', round($row['5'] * 86400) - 3600);;

                    }

                    $this->writeLog('test', $values);
                } else {
                    $error = 'File could not be read';
                }
            }

            if($error) {
                echo json_encode(array('status' => 2, 'error' => $error));
            } else {
                echo json_encode(array('status' => 1));
            }

            exit;
        }
        $this->view('upload' . DS . 'index');
    }
}