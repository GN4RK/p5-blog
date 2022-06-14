<?php
declare(strict_types=1);

namespace App\Model;

class PostSG {

    public static function set(string $key, mixed $value): void {
        $_POST[$key] = $value;
    }

    public static function get(string $key): mixed {
        return (isset($_POST[$key]) ? $_POST[$key] : null);
    }

    public static function forget(string $key): void {
        unset($_POST[$key]);
    }

    public static function getAll(): array {
        return $_POST;
    }

}