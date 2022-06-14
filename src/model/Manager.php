<?php
declare(strict_types=1);

namespace App\Model;

/**
 * Manager class
 */
abstract class Manager {
        
    /**
     * Connect to the database
     *
     * @return PDO
     */
    protected function dbConnect(): \PDO {
        $db = new \PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '');
        return $db;
    }
}