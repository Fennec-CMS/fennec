<?php
/**
 * @TODO Use a better way to translate menu item names
 */
return array(
    $this->translate('Dashboard') => array(
        'route' => 'admin',
        'icon' => 'fa fa-dashboard'
    ),
    $this->translate('Administrators') => array(
        'route' => 'admin-administrators-list',
        'icon' => 'fa fa-user',
        'subitems' => array(
            $this->translate('Create new') => 'admin-administrators-add',
            $this->translate('List') => 'admin-administrators-list'
        )
    )
);
