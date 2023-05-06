<?php
namespace App;
class App
{
    private array $routes = [];
    public function run(): void{

        $handler = $this->route();
        list($view, $params) = require_once $handler;
        extract($params);

        ob_start();
        include $view;
        $content = ob_get_clean();

        $layout = file_get_contents('./views/layout.html');
        echo $result = str_replace('{content}', $content, $layout);

    }
    private function route(): ?string
    {
        $requestUri=$_SERVER['REQUEST_URI'];
        foreach ($this->routes as $pattern => $handler){
            if (preg_match("#$pattern#", $requestUri, $params)) {
                if (file_exists($handler)) {
                    return $handler;
                }
            }
        }
        return "./handlers/notfound.php";
    }

    public function addRoute($route, $handlerPath){
        $this->routes[$route] = $handlerPath;
    }
}