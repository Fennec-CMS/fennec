<?php
use Fennec\Library\Router;

$routes = array(
    array(
        'name' => 'base',
        'route' => '/pagina/',
        'controller' => 'Index',
        'action' => 'index',
        'layout' => 'Default'
    )
);

foreach ($routes as $route) {
    Router::addRoute($route);
}
