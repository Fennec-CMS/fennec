<?php
namespace Fennec\Library;

class Router
{

    public static $routes = array();

    public static $matchingRoute = null;

    public static $view;

    public static $params = array();

    public static function addRoute(array $route)
    {
        $route['route'] = "/" . str_replace("/", "\\/", $route['route']) . "/is";
        self::$routes[$route['name']] = $route;
    }

    public static function dispatch()
    {
        $url = $_SERVER['REQUEST_URI'];
        foreach (self::$routes as $route) {
            if (preg_match($route['route'], $url, $matches)) {
                
                array_shift($matches);
                self::$params = array_combine($route['params'], $matches);
                
                self::$matchingRoute = $route;
                
                self::$view = $route['controller'] . "/" . $route['action'];
                
                return;
            }
        }
    }
}