<?php
/**
 * Created by PhpStorm.
 * User: asus1
 * Date: 13.07.2015
 * Time: 1:31
 */
class base
{

    /**
     * @param $model
     * @param string $table
     * @param string $db
     * @param string $user
     * @param string $password
     * @return model
     */

    protected function model($model, $table = null, $db = null, $user = null, $password = null)
    {
        $models = registry::get('models');
        if(!$m = $models[$model][$table]) {
            $model_file = ROOT_DIR . 'models' . DS . $model . '_model.php';
            if(file_exists($model_file)) {
                $model_class = $model . '_model';
                $m = new $model_class($table ? $table : $model, $db, $user, $password);
            } else {
                $m = new default_model($model);
            }
            $models[$model][$table] = $m;
            registry::remove('models');
            registry::set('models', $models);
        }
        return $m;
    }

    /**
     * @param string $file
     * @param mixed $value
     * @param string $mode
     */

    protected function writeLog($file, $value, $mode = 'a+') {
        $f = fopen(ROOT_DIR . DS . 'logs' . DS . $file . '.log', $mode);
        fwrite($f, date('Y-m-d H:i:s') . ' - ' .print_r($value, true) . "\n");
        fclose($f);
    }

    /**
     * @param string $key
     * @return string
     */

    protected function getConfig($key)
    {
        if(!$key) {
            return false;
        }
        if(!$value = registry::get('config')[$key]) {
            $config = registry::get('config');
            $config[$key] = $this->model('asanatt_system_config')->getByField('config_key', $key)['config_value'];
            registry::remove('config');
            registry::set('config', $config);
            return $config[$key];
        } else {
            return $value;
        }
    }

    /**
     * @param $key
     * @param $value
     */

    protected function setConfig($key, $value)
    {
        $row = $this->model('asanatt_system_config')->getByField('config_key', $key);
        $row['config_value'] = $value;
        $this->model('asanatt_system_config')->insert($row);
        $config = registry::get('config');
        $config[$key] = $value;
        registry::remove('config');
        registry::set('config', $config);
    }

    protected function getLocale($table, $key)
    {
        $row = array(
            'language' => registry::get('language'),
            'locale_key' => $key,
            'locale_table' => $table
        );
        return $this->model('locale')->getByFields($row)['locale_value'];
    }

    protected function getAllLocale($table)
    {

    }

    protected function api()
    {
        if(!$api = registry::get('asana_api')) {
            $api = new api_class();
            registry::set('asana_api', $api);
        }
        return $api;
    }
}