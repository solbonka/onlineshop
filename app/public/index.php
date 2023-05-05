<?php
$requestUri=$_SERVER['REQUEST_URI'];

require_once route($requestUri);

function route(string $requestUri): string
{
    if (preg_match('#/(?<route>[a-z0-9-_]+)#', $requestUri, $params)) {
        if (file_exists("./{$params['route']}.php")) {
            return "./{$params['route']}.php";
        }
    }
    return "./forms/notfound.phtml";
}