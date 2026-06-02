<?php

return [

    'sidebar' => [

        [
            'heading' => 'Platform',
            'items' => [
                [
                    'label' => 'Dashboard',
                    'icon' => 'home',
                    'route' => 'dashboard',
                    'permission' => null,
                ],
            ],
        ],

        [
            'heading' => 'Access Control',
            'items' => [
                [
                    'label' => 'Roles & Permissions',
                    'icon' => 'shield-check',
                    'route' => 'roles.index',
                    'permission' => 'role.view',
                    'active' => [
                        'roles.*', // wildcard
                    ],
                ],
            ],
        ],

        [
            'heading' => 'User Management',
            'items' => [
                [
                    'label' => 'Users',
                    'icon' => 'users',
                    'route' => 'users.index',
                    'permission' => 'user.view',
                    'active' => [
                        'users.*', // wildcard
                    ],
                ],
            ],
        ],

        [
            'heading' => 'Inventory Management',
            'items' => [
                [
                    'label' => 'Categories',
                    'icon' => 'squares-2x2',
                    'route' => 'categories.index',
                    'permission' => 'category.view',
                    'active' => [
                        'categories.*',
                    ],
                ],
                [
                    'label' => 'Items',
                    'icon' => 'squares-2x2',
                    'route' => 'items.index',
                    'permission' => 'item.view',
                    'active' => [
                        'items.*',
                    ],
                ],
                [
                    'label' => 'Suppliers',
                    'icon' => 'squares-2x2',
                    'route' => 'suppliers.index',
                    'permission' => 'supplier.view',
                    'active' => [
                        'suppliers.*',
                    ],
                ],
            ],
        ],

    ],

];
