<?php
namespace App;
class App
{
    private array $routes = [];
    public function run(): void{

        $handler = $this->route();

        if (is_array($handler)){
            list($obj, $method) = $handler;
            if(!is_object($obj)){
                $obj = new $obj();
            }
            $response = $obj->$method();
        }
        else{
            $response = $handler;
        }

        list($view, $params) = $response;
        extract($params);

        ob_start();
        include $view;
        $content = ob_get_clean();
        $layout = file_get_contents('../views/layout.html');
        $result = str_replace('{content}', $content, $layout);
        echo $result;
    }
    private function route(): array|callable|null
    {
        $requestUri=$_SERVER['REQUEST_URI'];

        foreach ($this->routes as $pattern => $handler)
        {
            if (preg_match("#^$pattern$#", $requestUri, $params))
            {
                return $handler;
            }
        }
        return ['App\Controller\UserController', 'notFound'];
    }


    public function addRoute(string $route,callable|array $callable):void{
        $this->routes[$route] = $callable;
    }
}