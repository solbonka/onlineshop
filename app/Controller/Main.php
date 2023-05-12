<?php

namespace App\Controller;

class Main
{
    public function main(): array
    {
        session_start();
        if (isset($_SESSION['id'])) {
            return [
                "../views/main.phtml",[]
            ];
        }
        header('Location: /signin');
        return [];
    }
}