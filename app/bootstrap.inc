<?php
chdir(dirname(__FILE__));

require '../vendor/autoload.php';

if (!defined('PUBLIC_CLIENT')) {
    throw new Exception('You have to set public client');
}

define('CONTROLLER_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . PUBLIC_CLIENT);
define('CONTROLLER_NAMESPACE', '\\App\\Http\\Controller\\' . PUBLIC_CLIENT . '\\');
define('VIEW_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . PUBLIC_CLIENT);
define('CONFIG_INI', __DIR__ . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'app.ini');
define('CONFIG_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'Config');
define('HELPER_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'Helper');

define("IS_SECURE", (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? true : false);
define("SITE_URL", "http" . (IS_SECURE ? "s" : "") . "://{$_SERVER["HTTP_HOST"]}/");
define("CURRENT_URL", "http" . (IS_SECURE ? "s" : "") . "://{$_SERVER["HTTP_HOST"]}{$_SERVER["REQUEST_URI"]}");

define("ENVIRONMENT", (getenv("ENVIRONMENT") ? getenv("ENVIRONMENT") : "production"));

if (defined("ENVIRONMENT")) {
    switch (ENVIRONMENT) {
        case "development":
            define("DEBUG_MODE", true);

            error_reporting(E_ALL);
            ini_set("display_errors", 1);

            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();

            break;
        case "production":
            define("DEBUG_MODE", false);

            error_reporting(0);
            ini_set("display_errors", 0);
            break;

        default:
            exit("The app environment is not set correctly.");
    }
}

$PI = new \App\Core\PI();
$PI->run();
