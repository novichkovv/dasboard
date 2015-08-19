<?php
/**
 * Created by PhpStorm.
 * User: asus1
 * Date: 11.08.2015
 * Time: 23:23
 */
class mapping_controller extends controller
{
    public function index()
    {
        $this->render('names', $this->model('mapping')->getNames());
        $this->view('mapping' . DS . 'index');
    }

    public function index_ajax()
    {
        switch($_REQUEST['action']) {
            case "mapping_table":
                $params = [];
                $params['table'] = 'user_mapping';
                $params['order'] = 'id DESC';
                $params['select'] = array(
                    'user_email',
                    'user_name',
                    'user_rate',
                    'CONCAT(
                "<a class=\"btn btn-xs btn-default edit_mapping\" href=\"#edit_modal\" data-toggle=\"modal\" data-id=\"", id, "\"><span class=\"fa fa-pencil\"></span> </a>",
                "<a class=\"btn btn-xs btn-default delete_mapping\" href=\"#delete_modal\" data-toggle=\"modal\" data-id=\"", id, "\"><span class=\"text-warning fa fa-times\"></span> </a>
                ")'
                );
                echo json_encode($this->getDataTable($params));
                exit;
                break;

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

            case "get_mapping":
                if(!$mapping = $this->model('user_mapping')->getById($_POST['id'])) {
                    echo json_encode(array('status' => 2, 'Wrong Mapping Id'));
                } else {
                    echo json_encode(array('status' => 1, 'result' => $mapping));
                }
                exit;
                break;

            case "delete_mapping":
                if(!$mapping = $this->model('user_mapping')->getById($_POST['id'])) {
                    echo json_encode(array('status' => 2, 'Wrong Mapping Id'));
                } else {
                    $this->model('user_mapping')->deleteById($_POST['id']);
                    echo json_encode(array('status' => 1, 'result' => $mapping));
                }
                exit;
                break;
        }
    }
}