<?php
declare(strict_types=1);

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

}