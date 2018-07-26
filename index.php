<?php
session_start();

require_once ("vendor/autoload.php");

use Slim\Slim;

$app = new Slim();

$app->config('debug', true);

require_once ("rotas/functions.php");
require_once ("rotas/site.php");
require_once ("rotas/admin.php");

// Admin
$app->run();
?>