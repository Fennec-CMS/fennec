<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 ************************************************************************
 */
namespace Fennec\Library;

/**
 * Fennec CMS router
 *
 * @author David Lima
 * @version b0.1
 */
class Router
{

    /**
     * All registered routes
     *
     * @var array
     */
    public static $routes = array();

    /**
     * Route that matches with current URI
     *
     * @var string
     */
    public static $matchingRoute = null;

    /**
     * View to load
     *
     * @var string
     */
    public static $view;

    /**
     * GET params
     *
     * @var array
     */
    public static $params = array();

    /**
     * Append a route
     *
     * @param array $route
     */
    public static function addRoute(array $route)
    {
        $route['route'] = "/" . str_replace("/", "\\/", $route['route']) . "/is";
        self::$routes[$route['name']] = $route;
    }

    /**
     * Search by a matching route and load it's controller/action
     */
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
                $controller->view = str_replace("\\", "/", $route['controller']) . "/" . $route['action'];
                $controller->loadLayout();

                return;
            }
        }

        $controller = new \Fennec\Controller\Base();
        $controller->layout('Default');
        $controller->throwHttpError(404);
    }
}
