<?php
require_once '../Autoloader.php';
Autoloader::register(dirname(__DIR__));

use App\App;
use App\Controller\Main;
use App\Controller\UserController;

$connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');
$userRepository = new \App\Repository\UserRepository($connection);
$obj = new UserController($userRepository);
$app = new App();
$app->get('/signup', [UserController::class, 'signUp']);
$app->post('/signup', [UserController::class, 'signUp']);
$app->get('/signin', [UserController::class, 'signIn']);
$app->post('/signin', [UserController::class, 'signIn']);
$app->get('/main', [Main::class, 'main']);
$app->run();