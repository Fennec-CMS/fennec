<?php
return array(
    'Dashboard' => array(
        'route' => 'admin',
        'icon' => 'fa fa-dashboard'
    ),
    'Administrators' => array(
        'route' => 'admin-administrators-list',
        'icon' => 'fa fa-user',
        'subitems' => array(
            'Create new' => 'admin-administrators-add',
            'List' => 'admin-administrators-list'
        )
    )
);
