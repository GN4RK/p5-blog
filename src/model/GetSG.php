<?php
declare(strict_types=1);

namespace App\Model;

/**
 * Class that can access to $_GET
 */
class GetSG 
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
        $_GET[$key] = $value;
    }
    
    /**
     * get value
     *
     * @param  string $key
     * @return mixed
     */
    public static function get(string $key): mixed 
    {
        return (isset($_GET[$key]) ? $_GET[$key] : null);
    }
    
    /**
     * Erase value
     *
     * @param  string $key
     * @return void
     */
    public static function forget(string $key): void 
    {
        unset($_GET[$key]);
    }

}