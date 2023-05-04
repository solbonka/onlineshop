<?php
$requestUri=$_SERVER['REQUEST_URI'];
if ($requestUri === '/signup') {
    require_once "./signup.php";
    require_once "./forms/signup.phtml";
}
elseif ($requestUri === '/signin')   {
    require_once "./signin.php";
    require_once "./forms/signin.phtml";
    }
elseif ($requestUri === '/main') {
    require_once "./main.phtml";
}
?>