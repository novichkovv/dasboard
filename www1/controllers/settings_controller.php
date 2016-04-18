<?php
/**
 * Created by PhpStorm.
 * User: asus1
 * Date: 19.08.2015
 * Time: 23:10
 */
class settings_controller extends controller
{
    public function index()
    {
        $this->view('settings' . DS . 'settings');
    }

    public function index_ajax()
    {
        switch ($_REQUEST['action']) {
            case "save_password":
                if($_POST['password'] && $_POST['password'] == $_POST['repeat_password']) {
                    $row = array(
                        'id' => registry::get('user')['id'],
                        'user_password' => md5($_POST['password'])
                    );
                    if($this->model('asanatt_users')->insert($row)) {
                        echo json_encode(array('status' => 1));
                        $this->logOut();
                        $this->auth(registry::get('user')['email'], md5($_POST['password']));
                        registry::remove('user');
                        $this->checkAuth();
                    } else {
                        echo json_encode(array('status' => 2));
                    }
                }
                exit;
                break;
        }
    }
}