<?php
declare(strict_types=1);

namespace App\Model;

class GetSG {

    public static function set(string $key, mixed $value): void {
        $_GET[$key] = $value;
    }

    public static function get(string $key): mixed {
        return (isset($_GET[$key]) ? $_GET[$key] : null);
    }

    public static function forget(string $key): void {
        unset($_GET[$key]);
    }

}