<?php
use App\Container;
use App\Controller\CartController;
use App\Controller\CategoryController;
use App\Controller\UserController;
use App\FileLogger;
use App\LoggerInterface;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Controller\MainController;
use App\Repository\ProductRepository;

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
    },
    ProductRepository::class => function(Container $container){
        $connection = $container->get('db');
        return new ProductRepository($connection);
    },
    CategoryRepository::class => function(Container $container){
        $connection = $container->get('db');
        return new CategoryRepository($connection);
    },
    MainController::class => function (Container $container){
        $productRepository = $container->get(ProductRepository::class);
        $categoryRepository = $container->get(CategoryRepository::class);
        return new MainController($productRepository, $categoryRepository);
    },
    CategoryController::class => function (Container $container){
        $productRepository = $container->get(ProductRepository::class);
        $categoryRepository = $container->get(CategoryRepository::class);
        return new CategoryController($productRepository, $categoryRepository);
    },
    CartRepository::class => function(Container $container){
        $connection = $container->get('db');
        return new CartRepository($connection);
    },
    CartController::class => function (Container $container){
        $cartProductsRepository = $container->get(CartProductRepository::class);
        $cartRepository = $container->get(CartRepository::class);
        $productRepository = $container->get(ProductRepository::class);
        $connection = $container->get('db');
        return new CartController($cartProductsRepository, $cartRepository, $productRepository, $connection);
    },
    CartProductRepository::class => function (Container $container){
        $connection = $container->get('db');
        return new CartProductRepository($connection);
    }
];