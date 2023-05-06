<?php
namespace App;
class App
{
    public function run(): void{
        $requestUri=$_SERVER['REQUEST_URI'];

        $handler = $this->route($requestUri);
        list($view, $params) = require_once $handler;
        extract($params);

        ob_start();
        include $view;
        $content = ob_get_clean();

        $layout = file_get_contents('./views/layout.html');
        echo $result = str_replace('{content}', $content, $layout);

    }
    public function route(string $uri): string
    {
        if (preg_match('#/(?<route>[a-z0-9-_]+)#', $uri, $params)) {
            if (file_exists("./handlers/{$params['route']}.php")) {
                return "./handlers/{$params['route']}.php";
            }
        }
        return "./handlers/notfound.php";
    }
}