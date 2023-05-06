<?php
session_start();
if (isset($_SESSION['id'])) {
    return [
        "./views/main.phtml",[]
    ];
} else {
    header('Location: /signin');
}