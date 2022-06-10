<?php
declare(strict_types=1);

namespace App\Model;

class GetSG {

    public static function set($key, $value): void {
        $_GET[$key] = $value;
    }

    public static function get($key) {
        return (isset($_GET[$key]) ? $_GET[$key] : null);
    }

    public static function forget($key): void {
        unset($_GET[$key]);
    }

}