<?php
/**
 * Created by PhpStorm.
 * User: enovichkov
 * Date: 26.03.15
 * Time: 12:42
 */
class users_controller extends controller
{
    public function index()
    {
        if(isset($_POST['delete_btn'])) {
            $this->model('asanatt_users')->deleteById($_POST['delete_id']);
            header('Location: ' . SITE_DIR . 'users/');
            exit;
        }
        $this->render('users', $this->model('asanatt_users')->getUsers());
        $this->view('users' . DS . 'list');
    }

    public function index_ajax()
    {
        switch($_REQUEST['action']) {
            case "get_users_table":
                $params = array();
                $params['table'] = 'asanatt_users u';
                $params['select'] = array(
                    'u.id',
                    'u.user_name',
                    'u.user_surname',
                    'g.group_name',
                    'u.email',
                    'DATE_FORMAT(u.create_date,"%d/%m/%Y")',
                    'CONCAT("<a href=\"' . SITE_DIR .'users/add/?id=", u.id, "\" class=\"btn btn-default btn-xs\">
                            <span class=\"fa fa-pencil\"></span>
                        </a>
                        <a href=\"#delete_user_modal\" class=\"btn btn-default btn-xs delete_user\" data-id=\"", u.id, "\" data-toggle=\"modal\" role=\"button\">
                            <span class=\"text-danger fa fa-times\"></span>
                        </a>")'
                );
                $params['join']['asanatt_user_groups'] = array(
                    'on' => 'u.user_group_id = g.id',
                    'as' => 'g',
                    'left' => true
                );
                echo json_encode($this->getDataTable($params));
                exit;
                break;
        }


    }

    public function add()
    {
        if(isset($_POST['save_user_btn'])) {
            $row = [];
            if($_GET['id']) {
                $row['id'] = $_GET['id'];
            } else {
                $row['create_date'] = date('Y-m-d H:i:s');
            }
            $row['email'] = $_POST['email'];
            $row['user_name'] = $_POST['user_name'];
            $row['user_surname'] = $_POST['user_surname'];
            $row['user_group_id'] = $_POST['user_group_id'];
            if($_POST['user_password']) {
                $row['user_password'] = md5($_POST['user_password']);
            }
            $this->model('asanatt_users')->insert($row);
            if($_POST['user_password']) {
                $this->logOut();
                $this->auth(registry::get('user')['email'], md5($_POST['user_password']));
                registry::remove('user');
                $this->checkAuth();
            }
            header('Location: ' . SITE_DIR . 'users/');
            exit;
        }

        $this->render('user_groups', $this->model('asanatt_user_groups')->getAll());
        if($_GET['id']) {
            $this->render('user', $this->model('asanatt_users')->getById($_GET['id']));
        }
        $this->view('users' . DS . 'add');
    }

    public function groups()
    {
        if(isset($_POST['delete_btn'])) {
            $this->model('asanatt_user_groups')->deleteById($_POST['delete_id']);
            header('Location: ' . SITE_DIR . 'users/groups/');
            exit;
        }
        $this->render('groups', $this->model('asanatt_user_groups')->getAll());
        $this->view('users' . DS . 'groups');
    }

    public function add_group()
    {
        if(isset($_POST['save_group_btn'])) {
            $row = [];
            if($_GET['id']) {
                $row['id'] = $_GET['id'];
            }
            $row['group_name'] = $_POST['group_name'];
            $this->model('asanatt_user_groups')->insert($row);
            header('Location: ' . SITE_DIR . 'users/groups/');
            exit;
        }

        if($_GET['id']) {
            $this->render('group', $this->model('asanatt_user_groups')->getById($_GET['id']));
        }
        $this->view('users' . DS . 'add_group');
    }

    public function permissions()
    {
        if(isset($_POST['save_permissions_btn'])) {
            $this->model('asanatt_system_routes_user_groups_relations')->deleteAll();
            foreach($_POST['permission'] as $user_group_id => $routes) {
                if($routes) {
                    foreach($routes as $system_route_id) {
                        $row = [];
                        $row['user_group_id'] = $user_group_id;
                        $row['system_route_id'] = $system_route_id;
                        $this->model('asanatt_system_routes_user_groups_relations')->insert($row);
                    }
                }
            }
            header('Location: ' . SITE_DIR . 'users/permissions/');
            exit;
        }
        $permissions = [];
        $tmp = $this->model('asanatt_system_routes_user_groups_relations')->getAll();
        foreach($tmp as $v) {
            $permissions[$v['user_group_id']][] = $v['system_route_id'];
        }
        $tmp = $this->model('asanatt_system_routes')->getByField('permitted', '0', true, 'position');
        $routes = [];
        foreach($tmp as $v) {
            if(!$v['parent']) {
                $routes[$v['id']] = $v;
            } else {
                $routes[$v['parent']]['children'][$v['id']] = $v;
            }
        }
        $groups = $this->model('asanatt_user_groups')->getAll();

        $result = [];
        foreach($groups as $v) {
            $result[$v['id']]['group_name'] = $v['group_name'];
            $result[$v['id']]['routes'] = $routes;
            foreach($result[$v['id']]['routes'] as $k => $route) {
                if(is_array($permissions[$v['id']]) && in_array($route['id'], $permissions[$v['id']])) {
                    $result[$v['id']]['routes'][$k]['checked'] = true;
                }
                if($route['children']) {
                    foreach($route['children'] as $key => $child) {
                        if(is_array($permissions[$v['id']]) && in_array($child['id'], $permissions[$v['id']])) {
                            $result[$v['id']]['routes'][$k]['children'][$key]['checked'] = true;
                        }
                    }
                }
            }
        }
        $this->render('result', $result);
        $this->view('users' . DS . 'permissions');
    }

    public function permissions_ajax()
    {
        switch($_REQUEST['action']) {
            case "get_charts_permissions":
                $permissions = [];
                $tmp = $this->model('asanatt_charts_user_groups_relations')->getAll();
                foreach($tmp as $v) {
                    $permissions[$v['user_group_id']][] = $v['chart_id'];
                }
                $tmp = $this->model('asanatt_charts')->getAll();
                $charts = [];
                foreach($tmp as $v) {
                    $charts[$v['id']] = $v;
                }
                $groups = $this->model('asanatt_user_groups')->getAll();

                $result = [];
                foreach($groups as $v) {
                    $result[$v['id']]['group_name'] = $v['group_name'];
                    $result[$v['id']]['charts'] = $charts;
                    foreach($result[$v['id']]['charts'] as $k => $chart) {
                        if(is_array($permissions[$v['id']]) && in_array($chart['id'], $permissions[$v['id']])) {
                            $result[$v['id']]['charts'][$k]['checked'] = true;
                        }
                    }
                }
                $this->render('result', $result);
                $this->view_only('users' . DS . 'ajax' . DS . 'charts_permissions');
                exit;
                break;

            case "save_permission":
                switch($key = array_keys($_POST['permission'])[0]) {
                    case "1":
                        $part = 'system_route';
                        break;
                    case "2":
                        $part = 'chart';
                        break;
                }
                //echo $part . 's_user_groups_relations';
                $this->model('asanatt_' . $part . 's_user_groups_relations')->deleteAll();
                if (isset($part)) {
                    foreach ($_POST['permission'][$key] as $user_group_id => $routes) {
                        if ($routes) {
                            foreach ($routes as $system_route_id) {
                                $row = [];
                                $row['user_group_id'] = $user_group_id;
                                $row[$part . '_id'] = $system_route_id;
                                $this->model('asanatt_' . $part . 's_user_groups_relations')->insert($row);
                            }
                        }
                    }
                    echo json_encode(array('status' => 1));
                } else {
                    echo json_encode(array('status' => 2));
                }
                exit;
                break;
        }
    }
}