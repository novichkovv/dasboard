<?php
/**
 * Created by PhpStorm.
 * User: asus1
 * Date: 20.04.2016
 * Time: 1:33
 */
class sql_controller extends controller
{

    public function __construct($controller, $action)
    {
        if(isset($_POST['log_out_btn'])) {
            $this->logOut();
            header('Location: ' . SITE_DIR);
            exit;
        }
        if(isset($_POST['login_btn'])) {
            if($this->auth($_POST['email'], md5($_POST['password']), $_POST['remember'])) {
                header('Location: ' . SITE_DIR);
            } else {
                $this->render('error', true);
            }
        }
        registry::set('log', array());
        $this->controller_name = $controller;
        $this->check_auth = $this->checkAuth();
        if($this->check_auth) {
//            $this->sidebar();
        }
        $this->action_name = $action . ($this->check_auth ? '_na' : '');
        $config = [];
        foreach ($this->model('asanatt_system_config')->getAll() as $v) {
            $config[$v['config_key']] = $v['config_value'];
        }
        registry::set('config', $config);
    }
    public function index()
    {
        echo '
        ' . $_POST['query'] . '
        <form method="post">
            <textarea name="query" cols="60" rows="10">' . $_POST['query'] . '</textarea><br><br>
            <input type="submit">
        </form>
        ';
        if($_POST['query'] && $res = $this->model('default')->query($_POST['query'])) {
            echo '<table border="1" cellspacing="0">';
            foreach ($res as $k => $row) {
                echo '<tr>';
                foreach ($row as $key => $val) {
                    echo '<th>';
                    echo $key;
                    echo '</th>';
                }
                echo '</tr>';
                break;
            }
            foreach ($res as $k => $row) {
                echo '<tr>';
                foreach ($row as $key => $val) {
                    echo '<td>';
                    echo $val;
                    echo '</td>';
                }

                echo '</tr>';
            }
            echo '</table>';

        }
    }

    public function index_na()
    {

    }
}