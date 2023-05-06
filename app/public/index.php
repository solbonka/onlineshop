<?php

spl_autoload_register(function ($class){
    $appRoot = dirname(__DIR__);
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    $file = preg_replace("#^App#", $appRoot, $file);

    if(file_exists($file)){
        require_once $file;
        return true;

    }
    return false;
});

use App\App;
$app = new App();
$app->run();