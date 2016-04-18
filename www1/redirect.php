<?php
/**
 * Created by PhpStorm.
 * User: asus1
 * Date: 16.03.2016
 * Time: 14:03
 */

session_start();
require_once('config.php');
require_once(CORE_DIR . 'registry.php');
require_once(CORE_DIR . 'autoload.php');
if(!empty($_GET['code'])) {
    $api = new api_class();
    $api->getAuthCode($_GET['code']);
}
//$token = $_GET['access_token'];
//$params = array(
//    "grant_type" => "refresh_token",
//    "code" => $_GET['code'],
//    "redirect_uri" => REDIRECT_URL,
//    "scope" => self::$scopes,
//    "client_id" => APP_ID,
//    "client_secret" => APP_SECRET,
//    "refresh_token" => $refresh_token
//);
//$curl = curl_init(EXCHANGE_TOKEN_URL);
//curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($curl, CURLOPT_POST, true);
//curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
//$response = curl_exec($curl);
//$res = json_decode($response, true);
//if(!empty($res['refresh_token'])) {
//    self::setConfigs('exchange_refresh_token', $res['refresh_token']);
//    self::$access_token = $res['access_token'];
//}
