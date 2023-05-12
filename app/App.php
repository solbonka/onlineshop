<?php
namespace App;
class App
{
    private array $routes = [];

    public function __construct(private Container $container)
    {
    }
    public function run(): void{
        try{
            $handler = $this->route();
            if (is_array($handler)){
                list($obj, $method) = $handler;
                if(!is_object($obj)){
                        $obj = $this->container->get($obj);
                }
                $response = $obj->$method();
            }
            else{
                $response = call_user_func($handler);
            }

            list($view, $params) = $response;
            extract($params);

            ob_start();
            include $view;
            $content = ob_get_clean();
            $layout = file_get_contents('../views/layout.html');
            $result = str_replace('{content}', $content, $layout);
            echo $result;
        } catch (\Throwable $exception) {
            $logger = $this->container->get(LoggerInterface::class);
            $data = ['Message' => $exception->getMessage(),
                   'File' => $exception->getFile(),
                   'Line' => $exception->getLine()
            ];
            $logger->error('Произошла ошибка во время обработки запроса', $data);
            require_once '../views/InternalError.phtml';
        }
    }
    private function route(): array|callable|null
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$method] as $pattern => $handler)
        {
            if (preg_match("#^$pattern$#", $requestUri))
            {
                return $handler;
            }
        }
        return ['App\Controller\NotFound', 'notFound'];
    }

    public function get(string $route, array|callable $handler): void
    {
        $this->routes['GET'][$route] = $handler;
    }

    public function post(string $route, array|callable $callable): void
    {
        $this->routes['POST'][$route] = $callable;
    }

}