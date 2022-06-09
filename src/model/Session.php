<?php
declare(strict_types=1);

namespace App\Model;

class Session {

    public static function set($key, $value): void {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return (isset($_SESSION[$key]) ? $_SESSION[$key] : null);
    }

    public static function forget($key): void {
        unset($_SESSION[$key]);
    }

    public static function getRole() : string {
        return (isset(self::get('user')['role']) ? self::get('user')['role'] : "visitor");
    }

}