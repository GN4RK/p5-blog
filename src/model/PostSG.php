<?php
declare(strict_types=1);

namespace App\Model;

class PostSG {

    public static function set($key, $value): void {
        $_POST[$key] = $value;
    }

    public static function get($key) {
        return (isset($_POST[$key]) ? $_POST[$key] : null);
    }

    public static function forget($key): void {
        unset($_POST[$key]);
    }

    public static function getAll(): array {
        return $_POST;
    }

}