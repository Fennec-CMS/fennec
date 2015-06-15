<?php
use Fennec\Library\Router;

$routes = array(
    array(
        'name' => 'sample-base',
        'route' => '/sample/',
        'module' => 'Sample',
        'controller' => 'Index',
        'action' => 'index',
        'layout' => 'Default'
    ),
    array(
        'name' => 'blog-sample',
        'route' => '/admin/sample/',
        'module' => 'Sample',
        'controller' => 'Admin\\Index',
        'action' => 'index',
        'layout' => 'Admin/Default'
    )
);

foreach ($routes as $route) {
    Router::addRoute($route);
}
