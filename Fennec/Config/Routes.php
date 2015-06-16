<?php
use Fennec\Library\Router;

$routes = array(
    array(
        'name' => 'base',
        'route' => '/',
        'controller' => 'Index',
        'action' => 'index',
        'layout' => 'Default'
    ),
    array(
        'name' => 'admin',
        'route' => '/admin/',
        'controller' => 'Admin\\Index',
        'action' => 'index',
        'layout' => 'Admin/Default'
    ),
    array(
        'name' => 'admin-login',
        'route' => '/admin/login/',
        'controller' => 'Admin\\Index',
        'action' => 'login',
        'layout' => 'Admin/Unauthenticated'
    ),
    array(
        'name' => 'admin-logout',
        'route' => '/admin/logout/',
        'controller' => 'Admin\\Index',
        'action' => 'logout',
        'layout' => 'Admin/Default'
    )
);

foreach ($routes as $route) {
    Router::addRoute($route);
}
