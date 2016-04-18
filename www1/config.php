<?php
/**
 * configuration file
 */
date_default_timezone_set('America/Denver');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', realpath($_SERVER['DOCUMENT_ROOT']) . DS);
define('CORE_DIR', realpath($_SERVER['DOCUMENT_ROOT']) . DS . 'core' . DS);
define('SITE_DIR', 'http://' . str_replace('http://', '', $_SERVER['HTTP_HOST'] . '/'));
define('TEMPLATE_DIR', ROOT_DIR . 'templates' . DS);
define('LIBS_DIR', ROOT_DIR . 'libs' . DS);
define('IMAGE_DIR', ROOT_DIR . DS . 'images' . DS);
define('DEVELOPMENT_MODE', true);

define('DB_NAME', 'dashboard');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');

define('APP_ID', '101462498556969');
define('APP_SECRET', 'd66652f30fcae206abbd34127a6c384e');
define('APP_URL', 'http://seiko.loc');
define('REDIRECT_URL', 'http://seiko.loc/redirect.php');
define('AUTH_URL', 'https://app.asana.com/-/oauth_token');
define('API_URL', 'https://app.asana.com/api/1.0/');

define('TRACKING_FREQUENCY', 10); //in seconds, defines how often an ajax request updates registered time