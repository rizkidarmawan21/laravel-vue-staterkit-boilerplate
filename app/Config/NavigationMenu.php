<?php

namespace App\Config;

use App\Helpers\MenuPermission;

class NavigationMenu
{
    public static function items(): array
    {
        return [
            [
                'items' => [
                    [
                        'title' => 'Dashboard',
                        'href' => '/dashboard',
                        'icon' => 'LayoutGrid',
                        'can' => 'view_dashboard'
                    ]
                ]
            ],
            [
                'label' => 'Core',
                'items' => [
                    [
                        'title' => 'User Management',
                        'href' => '/users',
                        'icon' => 'User',
                        'can' => 'view_user_management'
                    ],
                    [
                        'title' => 'Role Management',
                        'href' => '/roles',
                        'icon' => 'User',
                        'can' => 'view_role_management'
                    ]
                ]
            ]
        ];
    }
}
