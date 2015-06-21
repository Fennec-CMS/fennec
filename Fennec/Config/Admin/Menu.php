<?php
return array(
    'Dashboard' => array(
        'route' => 'admin',
    ),
    'Administrators' => array(
        'route' => 'admin-administrators-list',
        'subitems' => array(
            'Create new' => 'admin-administrators-add',
            'List' => 'admin-administrators-list'
        )
    )
);
