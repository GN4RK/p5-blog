<?php
declare(strict_types=1);

namespace App\Model;

abstract class Manager
{
    protected function dbConnect(): \PDO {
        $db = new \PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '');
        return $db;
    }
}