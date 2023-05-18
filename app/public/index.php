<?php
require_once '../Autoloader.php';
Autoloader::register(dirname(__DIR__));

use App\App;
use App\Container;
use App\Controller\CartController;
use App\Controller\CategoryController;
use App\Controller\MainController;
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
$app->get('/main', [MainController::class, 'main']);
$app->get('/category/(?<category_id>[0-9]+)', [CategoryController::class, 'category']);
$app->get('/cart', [CartController::class, 'cart']);
$app->run();