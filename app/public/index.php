<?php
require_once '../Autoloader.php';
Autoloader::register(dirname(__DIR__));

use App\App;
use App\Controller\Main;
use App\Controller\UserController;
use App\Repository\UserRepository;

$container = new \App\Container();
$container->set(UserController::class, function (\App\Container $container){
    $userRepository = $container->get(UserRepository::class);
    $obj = new UserController($userRepository);
    return $obj;
});
$container->set(UserRepository::class, function (){
    $connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');
    $userRepository = new \App\Repository\UserRepository($connection);
    return $userRepository;
});

$app = new App($container);
$app->get('/signup', [UserController::class, 'signUp']);
$app->post('/signup', [UserController::class, 'signUp']);
$app->get('/signin', [UserController::class, 'signIn']);
$app->post('/signin', [UserController::class, 'signIn']);
$app->get('/main', [Main::class, 'main']);
$app->run();