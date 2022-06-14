<?php
declare(strict_types=1);

namespace App\Model;

/**
 * Class that can access to $_SESSION 
 */
class Session {
    
    /**
     * set value
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public static function set(string $key, mixed $value): void {
        $_SESSION[$key] = $value;
    }
    
    /**
     * get value
     *
     * @param  string $key
     * @return mixed
     */
    public static function get(string $key): mixed {
        return (isset($_SESSION[$key]) ? $_SESSION[$key] : null);
    }
    
    /**
     * Return $_SESSION array
     *
     * @return array
     */
    public static function getAll(): array {
        return $_SESSION;
    }
    
    /**
     * Erase value
     *
     * @param  string $key
     * @return void
     */
    public static function forget(string $key): void {
        unset($_SESSION[$key]);
    }
    
    /**
     * Return the role of the current user
     *
     * @return string
     */
    public static function getRole() : string {
        return (isset(self::get('user')['role']) ? self::get('user')['role'] : "visitor");
    }

}