<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Route
 */
class Route 
{
    public static $routes = Array();
    private static $pathNotFound = null;
    private static $methodNotAllowed = null;

    /**
      * Function used to add a new route
      * @param string $expression    Route string or expression
      * @param callable $function    Function to call if route with allowed method is found
      * @param string|array $method  Either a string of allowed method or 
      *                              an array with string values
      */
    public static function add(
        string $expression, 
        callable $function, 
        string|array $method = 'get'
    ): void {
        array_push(self::$routes, Array(
            'expression' => $expression,
            'function' => $function,
            'method' => $method
        ));
    }
    
    /**
     * Return all routes
     *
     * @return array
     */
    public static function getAll(): array 
    {
        return self::$routes;
    }
    
    /**
     * Call the function set in case the path is not found
     *
     * @param  mixed $function
     * @return void
     */
    public static function pathNotFound(callable $function): void 
    {
        self::$pathNotFound = $function;
    }
    
    /**
     * Call the function set in case the function is not allowed
     *
     * @param  mixed $function
     * @return void
     */
    public static function methodNotAllowed(callable $function): void 
    {
        self::$methodNotAllowed = $function;
    }
    
    /**
     * Check all path set with current URI
     *
     * @param  string $basepath
     * @param  bool $case_matters
     * @param  bool $tsl If the trailing slash matters
     * @param  bool $multimatch
     * @return void
     */
    public static function run(
        string $basepath = '', 
        bool $case_matters = false, 
        bool $tsl = false, 
        bool $multimatch = false
    ): void {
        // The basepath never needs a trailing slash
        // Because the trailing slash will be added using the route expressions
        $basepath = rtrim($basepath, '/');

        // Parse current URL
        $parsed_url = isset($_SERVER['REQUEST_URI']) 
            ? parse_url($_SERVER['REQUEST_URI']) 
            : BASEFOLDER;

        $path = '/';

        // If there is a path available
        if (isset($parsed_url['path'])) {
            // If the trailing slash matters
            if ($tsl) {
                $path = $parsed_url['path'];
            } else {
                // If the path is not equal to the base path (including a trailing slash)
                if ($basepath.'/' != $parsed_url['path']) {
                    // Cut the trailing slash away because it does not matters
                    $path = rtrim($parsed_url['path'], '/');
                } else {
                    $path = $parsed_url['path'];
                }
            }
        }

        $path = urldecode($path);

        // Get current request method
        $method = isset($_SERVER['REQUEST_METHOD']) 
            ? $_SERVER['REQUEST_METHOD'] 
            : 'GET';

        $path_match_found = false;

        $route_match_found = false;

        foreach (self::$routes as $route) {
            // If the method matches check the path
            // Add basepath to matching string
            if (
                $basepath != ''
                && $basepath != '/'
            ) {
                $route['expression'] = '('.$basepath.')'.$route['expression'];
            }

            // Add 'find string start' automatically
            $route['expression'] = '^'.$route['expression'];

            // Add 'find string end' automatically
            $route['expression'] = $route['expression'].'$';

            // Check path match
            if (preg_match(
                '#'.$route['expression'].'#'.($case_matters ? '' : 'i').'u', 
                $path, 
                $matches
            )) {
                $path_match_found = true;
                // Cast allowed method to array if it's not one already, then run through all methods
                foreach ((array)$route['method'] as $allowedMethod) {
                    // Check method match
                    if (strtolower($method) == strtolower($allowedMethod)) {
                        // Always remove first element. This contains the whole string
                        array_shift($matches); 

                        if ($basepath != '' && $basepath != '/') {
                            array_shift($matches); // Remove basepath
                        }

                        if ($return_value = call_user_func_array(
                            $route['function'], 
                            $matches
                        )) {
                            echo $return_value;
                        }

                        $route_match_found = true;

                        // Do not check other routes
                        break;
                    }
                }
            }

            // Break the loop if the first found route is a match
            if ($route_match_found && !$multimatch) {
                break;
            }

        }

        // No matching route was found
        if (!$route_match_found) {
            // But a matching path exists
            if ($path_match_found) {
                if (self::$methodNotAllowed) {
                    call_user_func_array(
                        self::$methodNotAllowed, 
                        Array($path,$method)
                    );
                }
            } else {
                if (self::$pathNotFound) {
                    call_user_func_array(
                        self::$pathNotFound, 
                        Array($path)
                    );
                }
            }
        }
    }
}