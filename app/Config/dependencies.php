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
use App\Service\CartService;
use App\ViewRenderer;

return [
    UserRepository::class => function (Container $container){
        $connection = $container->get('db');
        return new UserRepository($connection);
    },
    UserController::class => function (Container $container){
        $userRepository = $container->get(UserRepository::class);
        $viewRenderer = $container->get(ViewRenderer::class);
        return new UserController($userRepository, $viewRenderer);
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
        $cartProductRepository = $container->get(CartProductRepository::class);
        $cartRepository = $container->get(CartRepository::class);
        $viewRenderer = $container->get(ViewRenderer::class);
        return new MainController($productRepository, $categoryRepository, $cartProductRepository, $cartRepository, $viewRenderer);
    },
    CategoryController::class => function (Container $container){
        $productRepository = $container->get(ProductRepository::class);
        $categoryRepository = $container->get(CategoryRepository::class);
        $cartProductRepository = $container->get(CartProductRepository::class);
        $cartRepository = $container->get(CartRepository::class);
        $viewRenderer = $container->get(ViewRenderer::class);
        return new CategoryController($productRepository, $categoryRepository, $cartProductRepository, $cartRepository, $viewRenderer);
    },
    CartRepository::class => function(Container $container){
        $connection = $container->get('db');
        return new CartRepository($connection);
    },
    CartController::class => function (Container $container){
        $cartProductsRepository = $container->get(CartProductRepository::class);
        $cartRepository = $container->get(CartRepository::class);
        $productRepository = $container->get(ProductRepository::class);
        $viewRenderer = $container->get(ViewRenderer::class);
        $cartService = $container->get(CartService::class);
        return new CartController($cartProductsRepository, $cartRepository,
            $productRepository, $viewRenderer, $cartService);
    },
    CartProductRepository::class => function (Container $container){
        $connection = $container->get('db');
        return new CartProductRepository($connection);
    },
    CartService::class => function (Container $container){
        $cartRepository = $container->get(CartRepository::class);
        $connection = $container->get('db');
        $cartProductsRepository = $container->get(CartProductRepository::class);
        return new CartService($cartRepository, $connection, $cartProductsRepository);
    },
    ViewRenderer::class => function (Container $container){
        return new ViewRenderer();
    }
];