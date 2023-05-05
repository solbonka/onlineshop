<?php
$requestUri=$_SERVER['REQUEST_URI'];

$handler = route($requestUri);
list($view, $params) = require_once $handler;
extract($params);
require_once $view;
function route(string $requestUri): string
{
    if (preg_match('#/(?<route>[a-z0-9-_]+)#', $requestUri, $params)) {
        if (file_exists("./{$params['route']}.php")) {
            return "./{$params['route']}.php";
        }
    }
    return "./forms/notfound.phtml";
}