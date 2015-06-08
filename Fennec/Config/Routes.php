<?php
use Fennec\Library\Router;

$routes = array(
    array(
        'name' => 'base',
        'route' => '/pagina/',
        'controller' => 'Index',
        'action' => 'index',
        'layout' => 'Default'
    ),
    array(
        'name' => 'admin',
        'route' => '/admin/',
        'controller' => 'Admin\\Index',
        'action' => 'index',
        'layout' => 'Default'
    )
);

foreach ($routes as $route) {
    Router::addRoute($route);
}
