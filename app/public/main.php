<?php
session_start();
if (isset($_SESSION['id'])) {
    return [
        "./forms/main.phtml",[]
    ];
} else {
    header('Location: /signin');
}