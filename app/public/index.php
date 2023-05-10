<?php
require_once '../Autoloader.php';
Autoloader::register(dirname(__DIR__));

use App\App;
$app = new App();
$app->get('/signup', [\App\Controller\UserController::class, 'signUp']);
$app->post('/signup', [\App\Controller\UserController::class, 'signUp']);
$app->get('/signin', [\App\Controller\UserController::class, 'signIn']);
$app->post('/signin', [\App\Controller\UserController::class, 'signIn']);
$app->get('/main', [\App\Controller\Main::class, 'main']);
$app->run();