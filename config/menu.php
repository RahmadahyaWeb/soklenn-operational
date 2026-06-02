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
            'heading' => 'Operations',
            'items' => [

                [
                    'label' => 'Orders',
                    'icon' => 'clipboard-document-list',
                    'route' => 'orders.index',
                    'permission' => 'order.view',
                    'active' => [
                        'orders.*',
                    ],
                ],

                [
                    'label' => 'Customers',
                    'icon' => 'users',
                    'route' => 'customers.index',
                    'permission' => 'customer.view',
                    'active' => [
                        'customers.*',
                    ],
                ],

                [
                    'label' => 'Services',
                    'icon' => 'sparkles',
                    'route' => 'services.index',
                    'permission' => 'service.view',
                    'active' => [
                        'services.*',
                    ],
                ],

            ],
        ],

        [
            'heading' => 'Inventory',
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
                    'icon' => 'archive-box',
                    'route' => 'items.index',
                    'permission' => 'item.view',
                    'active' => [
                        'items.*',
                    ],
                ],

                [
                    'label' => 'Stock Adjustments',
                    'icon' => 'arrows-right-left',
                    'route' => 'stock-adjustments.index',
                    'permission' => 'stock-adjustment.view',
                    'active' => [
                        'stock-adjustments.*',
                    ],
                ],

                [
                    'label' => 'Suppliers',
                    'icon' => 'truck',
                    'route' => 'suppliers.index',
                    'permission' => 'supplier.view',
                    'active' => [
                        'suppliers.*',
                    ],
                ],

                [
                    'label' => 'Item Purchases',
                    'icon' => 'shopping-cart',
                    'route' => 'item-purchases.index',
                    'permission' => 'item-purchase.view',
                    'active' => [
                        'item-purchases.*',
                    ],
                ],

            ],
        ],

        [
            'heading' => 'Finance',
            'items' => [

                [
                    'label' => 'Incomes',
                    'icon' => 'arrow-trending-up',
                    'route' => 'incomes.index',
                    'permission' => 'income.view',
                    'active' => [
                        'incomes.*',
                    ],
                ],

                [
                    'label' => 'Expense Categories',
                    'icon' => 'tag',
                    'route' => 'expense-categories.index',
                    'permission' => 'expense-category.view',
                    'active' => [
                        'expense-categories.*',
                    ],
                ],

                [
                    'label' => 'Expenses',
                    'icon' => 'arrow-trending-down',
                    'route' => 'expenses.index',
                    'permission' => 'expense.view',
                    'active' => [
                        'expenses.*',
                    ],
                ],

            ],
        ],

        [
            'heading' => 'Administration',
            'items' => [

                [
                    'label' => 'Users',
                    'icon' => 'user-group',
                    'route' => 'users.index',
                    'permission' => 'user.view',
                    'active' => [
                        'users.*',
                    ],
                ],

                [
                    'label' => 'Roles & Permissions',
                    'icon' => 'shield-check',
                    'route' => 'roles.index',
                    'permission' => 'role.view',
                    'active' => [
                        'roles.*',
                    ],
                ],

            ],
        ],

    ],

];
