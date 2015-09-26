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
    ),
    array(
        'name' => 'admin-administrators-list',
        'route' => '/admin/administrators/',
        'controller' => 'Admin\\Administrators',
        'action' => 'list',
        'layout' => 'Admin/Default'
    ),
    array(
        'name' => 'admin-administrators-add',
        'route' => '/admin/administrators/create/',
        'controller' => 'Admin\\Administrators',
        'action' => 'create',
        'layout' => 'Admin/Default'
    ),
    array(
        'name' => 'admin-administrators-delete',
        'route' => '/admin/administrators/delete/([0-9]+)/',
        'params' => array(
            'id'
        ),
        'controller' => 'Admin\\Administrators',
        'action' => 'delete',
        'layout' => 'Admin/Default'
    ),
    array(
        'name' => 'admin-administrators-profile',
        'route' => '/admin/administrators/profile/([0-9]+)/',
        'params' => array(
            'id'
        ),
        'controller' => 'Admin\\Administrators',
        'action' => 'profile',
        'layout' => 'Admin/Default'
    ),
    array(
        'name' => 'admin-modules',
        'route' => '/admin/modules/',
        'controller' => 'Admin\\Modules',
        'action' => 'list',
        'layout' => 'Admin/Default'
    ),
    array(
        'name' => 'admin-modules-available',
        'route' => '/admin/modules/available/',
        'controller' => 'Admin\\Modules',
        'action' => 'available',
        'layout' => 'Admin/Default'
    ),
    array(
        'name' => 'admin-module-install',
        'route' => '/admin/modules/install/([a-zA-Z0-9]+)/',
        'params' => array(
            'name'
        ),
        'controller' => 'Admin\\Modules',
        'action' => 'install',
        'layout' => 'Admin/Default'
    )
);

foreach ($routes as $route) {
    Router::addRoute($route);
}
