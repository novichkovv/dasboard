<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 06.03.15
 * Time: 19:47
 */
abstract class controller extends base
{
    protected $vars = array();
    protected $args;
    protected $system_header;
    protected $header;
    protected $footer;
    protected $system_footer;
    protected $controller_name;
    protected $action_name;
    protected $sidebar;
    private $redirect;
    public  $check_auth;
    protected $scripts = array();

    function __construct($controller, $action)
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
            $this->sidebar();
        }
        $this->action_name = $action . ($this->check_auth ? '_na' : '');
    }

    /**
     * @param string $template
     * @return string
     * @throws Exception
     */

    public function fetch($template)
    {
        $template_file = ROOT_DIR . 'templates' . DS . $template . '.php';
        if(!file_exists($template_file)) {
            throw new Exception('cannot find template in ' . $template_file);
        }
        foreach($this->vars as $k => $v) {
            $$k = $v;
        }
        ob_start();
        @require($template_file);
        return ob_get_clean();
    }

    /**
     * @param string $template
     * @throws Exception
     */

    protected function view($template)
    {
        $this->render('log', registry::get('log'));
        $template_file = ROOT_DIR . 'templates' . DS . $template . '.php';
        if(!file_exists($template_file)) {
            throw new Exception('Can not find template in ' . $template_file);
        }
        $this->render('scripts', $this->scripts);
        foreach($this->vars as $k => $v) {
            $$k = $v;
        }
        if($this->system_header !== false) {
            require_once(!$this->system_header ? ROOT_DIR . 'templates' . DS . 'system_header.php' : ROOT_DIR . 'templates' . DS . $this->system_header . '.php');
        }

        if($this->header !== false) {
            require_once(!$this->header ? ROOT_DIR . 'templates' . DS . 'header.php' : ROOT_DIR . 'templates' . DS . $this->header . '.php');
        }
        if($this->sidebar !== false) {
            require_once(!$this->header ? ROOT_DIR . 'templates' . DS . 'sidebar.php' : ROOT_DIR . 'templates' . DS . $this->sidebar() . '.php');
        }
        if($template_file !== false) {
            require_once($template_file);
        }
        if($this->footer !== false) {
            require_once(!$this->footer ? ROOT_DIR . 'templates' . DS . 'footer.php' : ROOT_DIR . 'templates' . DS . $this->footer . '.php');
        }
        if($this->system_footer !== false) {
            require_once(!$this->system_footer ? ROOT_DIR . 'templates' . DS . 'system_footer.php' : ROOT_DIR . 'templates' . DS . $this->system_footer . '.php');
        }
    }

    /**
     * @param string $template
     * @throws Exception
     */

    protected function view_only($template)
    {
        $this->render('log', registry::get('log'));
        $template_file = ROOT_DIR . 'templates' . DS . $template . '.php';
        if(!file_exists($template_file)) {
            throw new Exception('cannot find template in ' . $template_file);
        }
        foreach($this->vars as $k => $v) {
            $$k = $v;
        }
        require_once($template_file);
    }

    abstract function index();

    /**
     * @param string $key
     * @param mixed $value
     */

    protected function render($key, $value)
    {
        $this->vars[$key] = $value;
    }

    public function four_o_four() {
        $this->view('404');
    }

    /**
     * @return bool
     */
    protected function checkAuth()
    {
        if($_SESSION['auth']) {
            if($user = $this->model('asanatt_users')->getByFields(array(
                'id' => $_SESSION['user']['id'],
                'email' => $_SESSION['user']['email'],
                'user_password' => $_SESSION['user']['user_password']
            ))
            ) {
                registry::set('auth', true);
                registry::set('user', $user);
                return true;
            } else {
                return false;
            }
        } elseif($_COOKIE['auth']) {
            if($user = $this->model('asanatt_users')->getByFields(array(
                'id' => $_COOKIE['id'],
                'email' => $_COOKIE['email'],
                'user_password' => $_COOKIE['user_password']
            ))) {
                registry::set('auth', true);
                registry::set('user', $user);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param string $email
     * @param string $password
     * @param bool $remember
     * @return bool
     */

    protected function auth($email, $password, $remember = false)
    {
        if(!$password) return false;
        if($user = $this->model('asanatt_users')->getByFields(array(
            'email' => $email,
            'user_password' => $password
        ))) {
            if(!$remember) {
                $_SESSION['user']['id'] = $user['id'];
                $_SESSION['user']['email'] = $user['email'];
                $_SESSION['user']['user_password'] = $user['user_password'];
                $_SESSION['auth'] = 1;
            } else {
                setcookie('id', $user['id'], time() + 3600 * 24 * 90);
                setcookie('email', $user['email'], time() + 3600 * 24 * 90);
                setcookie('user_password', $user['user_password'], time() + 3600 * 24 * 90);
                setcookie('auth', 1, time() + 3600 * 24 * 90);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return void
     */

    protected function logOut()
    {
        unset($_SESSION['user']);
        unset($_SESSION['auth']);
        setcookie('id', '', time() - 3600);
        setcookie('email', '', time() - 3600);
        setcookie('user_password', '', time() - 3600);
        setcookie('auth', '', time() - 3600);
    }

    private function sidebar()
    {
        $system_route = trim($_REQUEST['route'], '/');
        $tmp = $this->model('asanatt_system_routes_user_groups_relations')->getByField('user_group_id', registry::get('user')['user_group_id'], true);
        $permissions = [];
        foreach($tmp as $v) {
            $permissions[$v['system_route_id']] = 1;
        }
        $sidebar = [];
        $tmp = $this->model('asanatt_system_routes')->getAll('position');
        $permit_page = false;
        $permitted_urls = [];

        foreach($tmp as $v) {
            if(!$v['parent']) {
                foreach($v as $key => $val) {
                    if($permissions[$v['id']] || $v['permitted']) {
                        if(!in_array($v['route'], $permitted_urls)) {
                            $permitted_urls[] = $v['route'];
                        }
                        if($v['route'] == $system_route || $v['permitted']) {
                            $permit_page = true;
                        }
                        if(!$v['hidden']) {
                            $sidebar[$v['id']][$key] = $val;
                        }
                    }
                }
            } else {
                foreach($v as $key => $val) {
                    if($permissions[$v['id']] || $v['permitted']) {
                        if(!in_array($v['route'], $permitted_urls)) {
                            $permitted_urls[] = $v['route'];
                        }
                        if($v['route'] == $system_route || $v['permitted']) {
                            $permit_page = true;
                        }
                        if(!$v['hidden']) {
                            $sidebar[$v['parent']]['children'][$v['id']][$key] = $val;
                        }
                    }
                }
            }
        }
        if(count($permitted_urls) == 1 && $system_route != $permitted_urls[0]) {
            header('Location: ' . SITE_DIR . $permitted_urls[0] . '/');
            exit;
        }
        if(count($permitted_urls) == 1) {
            $sidebar = false;
        }
        if(!$permit_page) {
            $this->view('access_denied');
            exit;
        }
        $this->render('sidebar', $sidebar);
    }

    /**
     * @param array $params
     * @param bool $print
     * @return mixed
     */

    public function getDataTable(array $params, $print = false)
    {
        $search = get_object_vars(json_decode($_REQUEST['params']));
        foreach($search as $k=>$v)
        {
            $params['where'][$k] = array(
                'sign' => $v->sign,
                'value' => $v->value
            );
        }
        $params['limits'] = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'].','.$_REQUEST['iDisplayLength'] : '';
        $params['order'] = $_REQUEST['iSortCol_0'] ? $params['select'][$_REQUEST['iSortCol_0']].($_REQUEST['sSortDir_0'] ? ' '.$_REQUEST['sSortDir_0'] : '') : '';
        $res = $this->model('default')->getFilteredData($params, $params['table']);
        if($print) {
            print_r($res);
        }
        $rows['aaData'] = $res['rows'];
        $rows['iTotalRecords'] = $this->model(explode(' ', $params['table'])[0])->countByField();
        $rows['iTotalDisplayRecords'] = $res['count'];
        return($rows);
    }

    /**
     * @param mixed $value
     */

    protected function log($value)
    {
        $log = registry::get('log');
        registry::remove('log');
        $log[] = print_r($value,1);
        registry::set('log', $log);
    }

    /**
     * @param mixed $file_name
     */

    protected function addScript($file_name) {
        if(is_array($file_name)) {
            foreach($file_name as $file) {
                $this->scripts[] = $file;
            }
        } else {
            $this->scripts[] = $file_name;
        }
    }

}
