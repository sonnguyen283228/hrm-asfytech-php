<?php

namespace App\Controllers;

abstract class Controller
{
    protected function view(string $file, array $data = []): void
    {
        \view($file, $data);
    }

    protected function redirect(string $path): void
    {
        \redirect($path);
    }
    
    protected function db(): \PDO
    {
        return \db();
    }
}
