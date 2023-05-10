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
                if ($obj instanceof ConnectionAwareInterface){
                    $obj->setConnection(new \PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd'));
                }
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
    }
    private function route(): array|callable|null
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$method] as $pattern => $handler)
        {
            if (preg_match("#^$pattern$#", $requestUri, $params))
            {
                return $handler;
            }
        }
        return ['App\Controller\NotFound', 'notFound'];
    }


    public function addRoute(string $route, callable|array $callable )
    {
      $this->routes[$route] = $callable;
    }

    public function get(string $route, array|callable $handler)
    {
        $this->routes['GET'][$route] = $handler;
    }

    public function post(string $route, array|callable $callable){
        $this->routes['POST'][$route] = $callable;
    }

}