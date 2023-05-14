<?php

namespace App\Controller;

class NotFound
{
    public function notFound(): array
    {
        return[
            "../views/notfound.phtml",
            [],
            false
        ];
    }
}