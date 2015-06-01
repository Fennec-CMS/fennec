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

                if (isset($route['params']) && $route['params']) {
                    array_shift($matches);
                    self::$params = array_combine($route['params'], $matches);
                }

                self::$matchingRoute = $route;

                $controller = "Fennec\\Controller\\{$route['controller']}";
                $action = $route['action'] . "Action";

                $controller = new $controller();
                $controller->layout($route['layout']);
                $controller->$action();
                $controller->view = $route['controller'] . "/" . $route['action'];
                $controller->loadLayout();

                return;
            }
        }

        $controller = new \Fennec\Controller\Base();
        $controller->layout('Default');
        $controller->throwHttpError(404);
    }
}