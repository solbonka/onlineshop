<?php
session_start();
if (isset($_SESSION['id'])) {
    print_r($_SESSION['id']);
    require_once './forms/main.phtml';
} else {
    header('Location: /signin');
}