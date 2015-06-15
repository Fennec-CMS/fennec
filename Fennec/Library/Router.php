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
        $route['route'] = "/^" . str_replace("/", "\\/", $route['route']) . "$/is";
        self::$routes[$route['name']] = $route;
    }

    /**
     * Search by a matching route and load it's controller/action
     */
    public static function dispatch()
    {
        $url = $_SERVER['REQUEST_URI'];
        $requestedRoute = strstr('/', $url);
        $requestedRoute = substr($url, $requestedRoute);
        
        if (! $requestedRoute){
            $requestedRoute = "/";
        }

        foreach (self::$routes as $route) {
            if (preg_match($route['route'], $requestedRoute, $matches)) {

                if (isset($route['params']) && $route['params']) {
                    array_shift($matches);
                    self::$params = array_combine($route['params'], $matches);
                }

                self::$matchingRoute = $route;

                $module = isset($route['module']) ? "Modules\\" . $route['module'] . "\\" : null;

                $controller = "Fennec\\{$module}Controller\\{$route['controller']}";
                $action = $route['action'] . "Action";

                $controller = new $controller();

                if ($controller->module !== false) {
                    $controller->module = $module;
                }

                if (! $controller->layout) {
                    $controller->layout($route['layout']);
                }

                if (! $controller->view) {
                    $controller->view = str_replace("\\", "/", ($module ? $module . 'View/' : null) . $route['controller']) . "/" . $route['action'];
                } else {
                    $action = strrpos($controller->view, '/') + 1;
                    $action = substr($controller->view, $action) . 'Action';

                    $controller->view = str_replace("\\", "/", $controller->view);
                }

                $controller->$action();

                $controller->loadLayout();

                return;
            }
        }
        $controller = new \Fennec\Controller\Base();
        $controller->layout('Default');
        $controller->throwHttpError(404);
    }
}
