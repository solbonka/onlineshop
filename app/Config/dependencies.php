<?php
use App\Container;
use App\Controller\UserController;
use App\FileLogger;
use App\LoggerInterface;
use App\Repository\UserRepository;

return [
    UserRepository::class => function (Container $container){
    $connection = $container->get('db');
    return new UserRepository($connection);
    },
    UserController::class => function (Container $container){
        $userRepository = $container->get(UserRepository::class);
        return new UserController($userRepository);
    },
    LoggerInterface::class => function(){
        return new FileLogger();
    },
    'db' => function(Container $container){
        $settings = $container->get('settings');
        $host = $settings['db']['host'];
        $user = $settings['db']['user'];
        $database = $settings['db']['database'];
        $password = $settings['db']['password'];
        return new PDO("pgsql:host=$host;dbname=$database", $user, $password);
    }
];