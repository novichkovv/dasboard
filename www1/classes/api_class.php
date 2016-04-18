<?php
/**
 * Created by PhpStorm.
 * User: asus1
 * Date: 29.03.2016
 * Time: 10:49
 */
class api_class extends base
{
    var $access_token;

    public function __construct()
    {
        $this->getAuthCode();
    }

    public function getAuthCode($code = null)
    {
        //$this->model('asanatt_system_config')->delete('config_key', 'refresh_token');
        //$this->setConfig('refresh_token', '0/0834fccaa064a3c97698f883100cdd22');
        $refresh_token = $this->getConfig('refresh_token');
        //echo $refresh_token; exit;
        if($code) {
            $params = array(
                "grant_type" => "authorization_code",
                "code" => $code,
                "redirect_uri" => REDIRECT_URL,
                "client_id" => APP_ID,
                "client_secret" => APP_SECRET,
            );
            $curl = curl_init(AUTH_URL);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
            $response = curl_exec($curl);
            $this->writeLog('test', $response);
            $res = json_decode($response, true);
            $this->setConfig('refresh_token', $res['refresh_token']);
            $this->access_token = $res['access_token'];
        } elseif($refresh_token) {
            $this->refreshToken($refresh_token);
        }
    }

    public function refreshToken($refresh_token)
    {
        $params = array(
            "grant_type" => "refresh_token",
            "code" => $_GET['code'],
            "redirect_uri" => REDIRECT_URL,
            "client_id" => APP_ID,
            "client_secret" => APP_SECRET,
            "refresh_token" => $refresh_token
        );
        $curl = curl_init(AUTH_URL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        $response = curl_exec($curl);
        $this->writeLog('test', $response);
        $res = json_decode($response, true);
        if(!empty($res['access_token'])) {
            $this->access_token = $res['access_token'];
        }
    }

    public function getWorkspaces()
    {
        return $this->makeApiCall(API_URL . 'workspaces', 'GET');
    }

    public function getAllUsers()
    {
        return $this->makeApiCall(API_URL . 'users?opt_fields=email,name', 'GET');
    }

    public function getUser($user_id){
        return $this->makeApiCall(API_URL . 'users/' . $user_id, 'GET');
    }

    public function getTasks($workspace_id, $user_id){
        $url = API_URL . 'workspaces/' . $workspace_id . '/tasks?assignee=' . $user_id . '&completed_since=now&opt_fields=name,modified_at,created_at,completed_at,due_on,assignee,completed,projects.name,subtasks.name';
        $res = $this->makeApiCall($url, 'GET');
        if(isset($_GET['debug'])) {
            print_r($res);
            exit;
        }
        foreach ($res['data'] as $k => $v) {
            $res['data'][$k]['id'] = number_format($v['id'], 0, '.', '');
            $res['data'][$k]['assignee']['id'] = number_format($v['assignee']['id'], 0, '.', '');
            if(!$v['name'] && !$v['projects']) {
                unset($res['data'][$k]);
            }
        }
        return $res;
    }

    public function getOneTask($task_id){
        $result = $this->makeApiCall(API_URL . 'tasks/' . $task_id, 'GET');
        if(!$result['errors']) {
            if(array_key_exists(0, $result['data']['projects'])) {
                $projects = (array)$result['data']['projects'][0];
            }
            elseif(is_array($result['data']['parent'])){
                $projects = array(
                    'id' => number_format($result['data']['parent']['id'], 0, '.', ''),
                    'name' => 'PARENT TASK: '.$result['data']['parent']['name']
                );
            }
            else{
                $projects = array(
                    'id' => number_format($result['data']['id'], 0, '.', ''),
                    'name' => 'NO PROJECT'
                );
            }
            return array ( 'completed' => $result['data']['completed'],
                'assignee' => number_format($result['data']['assignee']['id'], 0, '.', ''),
                'projects' => $projects
            );
        }
        else return false;
    }

    public function getSubTasks($task_id){
        $resultArr = $this->makeApiCall(API_URL . 'tasks/'.$task_id.'/subtasks', 'GET');

        $html = '<ul>';
        if (!empty($resultArr['data'])) {
            foreach ($resultArr['data'] as $key => $value) {
                if ($value['name'])
                    $html .= '<li>' . $value['name'] . '</li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }

    public function updateTask($taskId, $workedHours, $workedMinutes, $estimatedHours, $estimatedMinutes, $taskName){

        $data = array( "name" => $taskName ." [ET: " . $estimatedHours . "h " . $estimatedMinutes . "m] [WT: " . $workedHours . "h " . $workedMinutes . "m]");
        $data = array("data" => $data);
        $data = json_encode($data);

        return $this->makeApiCall(API_URL . 'tasks/' . $taskId, 'PUT', $data);
    }

    public function checkTimeFilled($taskName){
        $pattern = "/\[ET\: (\d+)h (\d+)m\] \[WT\: (\d+)h (\d+)m\]$/";

        if(preg_match($pattern, $taskName, $matches))
            return TRUE;

        return FALSE;

    }

    public function getEstimatedAndWorkedTime($taskName){
        $estimatedTimeHours = 0;
        $estimatedTimeMinutes = 0;
        $workedTimeHours = 0;
        $workedTimeMinutes = 0;
        $workedTime = 0;

        $pattern = "/\[ET\: (\d+)h (\d+)m\] \[WT\: (\d+)h (\d+)m\]$/";

        if(preg_match($pattern, $taskName, $matches)) {

            // estimated time
            $estimatedTimeHours = $matches[1];
            $estimatedTimeMinutes = $matches[2];

            // worked time
            $workedTimeHours = $matches[3];
            $workedTimeMinutes = $matches[4];

            // worked time in sec
            $workedTime = $workedTimeHours * 60 * 60 * 1000;
            $workedTime += $workedTimeMinutes * 60 * 1000;

            $taskName = preg_replace($pattern, "", $taskName);
        }

        $array = array ( 'taskName' => $taskName,
            'estimatedHours' => $estimatedTimeHours,
            'estimatedMinutes' => $estimatedTimeMinutes,
            'workedHours' => $workedTimeHours,
            'workedMinutes' => $workedTimeMinutes,
            'workedTimeSec' => $workedTime
        );

        return $array;

    }


    public function getProjects($workspaceId){
        $resultArr = $this->makeApiCall(API_URL . 'workspaces/'.$workspaceId .'/projects', 'GET');
        $data = array();
        if(!empty($resultArr['data'])){
            foreach ($resultArr['data'] as $key => $value) {
                $data[$value['id']] = $value['name'];
            }
        }
        return $data;
    }


    private function makeApiCall($url, $method, array $params = array())
    {
        $headers = array(
            "Authorization: Bearer " . $this->access_token,
        );
        $curl = curl_init($url);
        switch ($method) {
            case "GET":
                break;
            case "POST":
                $headers[] = "Content-Type: application/json";
                curl_setopt($curl, CURLOPT_POST, true);
                $params = $params ? json_encode($params) : json_encode($params, JSON_FORCE_OBJECT);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);


        $response = curl_exec($curl);
        $sent_headers = curl_getinfo($curl, CURLINFO_HEADER_OUT);
        return json_decode($response, true);
    }
}