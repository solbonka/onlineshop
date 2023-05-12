<?php
require_once '../Autoloader.php';
Autoloader::register(dirname(__DIR__));

use App\App;
use App\Container;
use App\Controller\Main;
use App\Controller\UserController;

$dependencies = include '../Config/dependencies.php';
$settings = include '../Config/settings.php';
$data = array_merge($dependencies, $settings);
$container = new Container($data);

$app = new App($container);
$app->get('/signup', [UserController::class, 'signUp']);
$app->post('/signup', [UserController::class, 'signUp']);
$app->get('/signin', [UserController::class, 'signIn']);
$app->post('/signin', [UserController::class, 'signIn']);
$app->get('/main', [Main::class, 'main']);
$app->run();