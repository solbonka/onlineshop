<?php
namespace App;
use App\Exception\ContainerException;

class App
{
    private array $routes = [];

    public function __construct(private Container $container)
    {
    }

    /**
     * @throws ContainerException
     */
    public function run(): void{
        //try{
            list($handler, $params) = $this->route();
            if(empty($handler)){
                require_once '../views/notfound.phtml'; die;
            }
            if (is_array($handler)){
                list($obj, $method) = $handler;
                if(!is_object($obj)){
                        $obj = $this->container->get($obj);
                }

                $response = $obj->$method(...$params);
            }
            else{
                $response = call_user_func($handler);

            }
            echo $response;
       //} catch (\Throwable $exception) {
       //    $logger = $this->container->get(LoggerInterface::class);
       //    $data = ['Message' => $exception->getMessage(),
       //           'File' => $exception->getFile(),
       //           'Line' => $exception->getLine()
       //    ];
       //    $logger->error('Произошла ошибка во время обработки запроса', $data);
       //    require_once '../views/InternalError.phtml';
       //}
    }
    private function route(): array|callable|null
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $params = [];
        foreach ($this->routes[$method] as $pattern => $handler)
        {

            if (preg_match("#^$pattern$#", $requestUri, $params))
            {
                if (empty($params)){
                    return null;
                }
                else {
                    foreach ($params as $key => $value) {
                        if($key === 0 || intval($key)){
                            unset($params[$key]);
                        }
                    }
                }
                $params = array_values($params);
                return [$handler, $params];
            }
        }
        return null;
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