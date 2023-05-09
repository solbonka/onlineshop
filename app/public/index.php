<?php
require_once '../Autoloader.php';
Autoloader::register(dirname(__DIR__));
use App\App;

$app = new App();

$app->addRoute('/signup', [\App\Controller\UserController::class, 'signUp']);
$app->addRoute('/signin', [\App\Controller\UserController::class, 'signIn']);
$app->addRoute('/main', [\App\Controller\UserController::class, 'main']);


$app->run();