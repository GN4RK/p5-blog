<?php
declare(strict_types=1);

namespace App\Model;

class Session {

    public static function set(string $key, mixed $value): void {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key): mixed {
        return (isset($_SESSION[$key]) ? $_SESSION[$key] : null);
    }

    public static function getAll(): array {
        return $_SESSION;
    }

    public static function forget(string $key): void {
        unset($_SESSION[$key]);
    }

    public static function getRole() : string {
        return (isset(self::get('user')['role']) ? self::get('user')['role'] : "visitor");
    }

}