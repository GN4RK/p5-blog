<?php
declare(strict_types=1);

namespace App\Model;

/**
 * Class that can access to $_POST 
 */
class PostSG 
{    
    /**
     * set value
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public static function set(string $key, mixed $value): void 
    {
        $_POST[$key] = $value;
    }
    
    /**
     * get value
     *
     * @param  string $key
     * @return mixed
     */
    public static function get(string $key): mixed 
    {
        return (isset($_POST[$key]) ? $_POST[$key] : null);
    }
    
    /**
     * Erase value
     *
     * @param  string $key
     * @return void
     */
    public static function forget(string $key): void 
    {
        unset($_POST[$key]);
    }
    
    /**
     * Return $_POST array
     *
     * @return array
     */
    public static function getAll(): array 
    {
        return $_POST;
    }

}