<?php

use Illuminate\Support\Facades\Route;

// Route::livewire('/', 'pages::landing-page')
//     ->name('home');

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('dashboard', 'pages::dashboard.index')
        ->name('dashboard');

    // ROLES & PERMISSIONS
    Route::prefix('roles')
        ->name('roles.')
        ->middleware(['permission:role.view'])
        ->group(function () {

            Route::livewire('/', 'pages::role.index')
                ->name('index');

            Route::livewire('/create', 'pages::role.form')
                ->middleware('permission:role.create')
                ->name('create');

            Route::livewire('/{role}/edit', 'pages::role.form')
                ->middleware('permission:role.update')
                ->name('edit');

        });

    // USER MANAGEMENT
    Route::prefix('users')
        ->name('users.')
        ->middleware(['permission:user.view'])
        ->group(function () {

            Route::livewire('/', 'pages::user.index')
                ->name('index');

            Route::livewire('/create', 'pages::user.form')
                ->middleware('permission:user.create')
                ->name('create');

            Route::livewire('/{user}/edit', 'pages::user.form')
                ->middleware('permission:user.update')
                ->name('edit');
        });

    // CATEGORY MANAGEMENT
    Route::prefix('categories')
        ->name('categories.')
        ->middleware(['permission:category.view'])
        ->group(function () {

            Route::livewire('/', 'pages::category.index')
                ->name('index');

            Route::livewire('/create', 'pages::category.form')
                ->middleware('permission:category.create')
                ->name('create');

            Route::livewire('/{category}/edit', 'pages::category.form')
                ->middleware('permission:category.update')
                ->name('edit');
        });

    // ITEM MANAGEMENT
    Route::prefix('items')
        ->name('items.')
        ->middleware(['permission:item.view'])
        ->group(function () {

            Route::livewire('/', 'pages::item.index')
                ->name('index');

            Route::livewire('/create', 'pages::item.form')
                ->middleware('permission:item.create')
                ->name('create');

            Route::livewire('/{item}/edit', 'pages::item.form')
                ->middleware('permission:item.update')
                ->name('edit');
        });

    // SUPPLIER MANAGEMENT
    Route::prefix('suppliers')
        ->name('suppliers.')
        ->middleware(['permission:supplier.view'])
        ->group(function () {

            Route::livewire('/', 'pages::supplier.index')
                ->name('index');

            Route::livewire('/create', 'pages::supplier.form')
                ->middleware('permission:supplier.create')
                ->name('create');

            Route::livewire('/{supplier}/edit', 'pages::supplier.form')
                ->middleware('permission:supplier.update')
                ->name('edit');
        });

    // SERVICE MANAGEMENT
    Route::prefix('services')
        ->name('services.')
        ->middleware(['permission:service.view'])
        ->group(function () {

            Route::livewire('/', 'pages::service.index')
                ->name('index');

            Route::livewire('/create', 'pages::service.form')
                ->middleware('permission:service.create')
                ->name('create');

            Route::livewire('/{service}/edit', 'pages::service.form')
                ->middleware('permission:service.update')
                ->name('edit');
        });

    // CUSTOMER MANAGEMENT
    Route::prefix('customers')
        ->name('customers.')
        ->middleware(['permission:customer.view'])
        ->group(function () {

            Route::livewire('/', 'pages::customer.index')
                ->name('index');

            Route::livewire('/create', 'pages::customer.form')
                ->middleware('permission:customer.create')
                ->name('create');

            Route::livewire('/{customer}/edit', 'pages::customer.form')
                ->middleware('permission:customer.update')
                ->name('edit');
        });

    // ORDER MANAGEMENT
    Route::prefix('orders')
        ->name('orders.')
        ->middleware(['permission:order.view'])
        ->group(function () {

            Route::livewire('/', 'pages::order.index')
                ->name('index');

            Route::livewire('/create', 'pages::order.form')
                ->middleware('permission:order.create')
                ->name('create');

            Route::livewire('/{order}/edit', 'pages::order.form')
                ->middleware('permission:order.update')
                ->name('edit');
        });

    // INCOME MANAGEMENT
    Route::prefix('incomes')
        ->name('incomes.')
        ->middleware(['permission:income.view'])
        ->group(function () {

            Route::livewire('/', 'pages::income.index')
                ->name('index');

            Route::livewire('/create', 'pages::income.form')
                ->middleware('permission:income.create')
                ->name('create');

            Route::livewire('/{income}/edit', 'pages::income.form')
                ->middleware('permission:income.update')
                ->name('edit');
        });

    // EXPENSE CATEGORY MANAGEMENT
    Route::prefix('expense-categories')
        ->name('expense-categories.')
        ->middleware(['permission:expense-category.view'])
        ->group(function () {

            Route::livewire('/', 'pages::expense-category.index')
                ->name('index');

            Route::livewire('/create', 'pages::expense-category.form')
                ->middleware('permission:expense-category.create')
                ->name('create');

            Route::livewire('/{expenseCategory}/edit', 'pages::expense-category.form')
                ->middleware('permission:expense-category.update')
                ->name('edit');
        });

    // EXPENSE MANAGEMENT
    Route::prefix('expenses')
        ->name('expenses.')
        ->middleware(['permission:expense.view'])
        ->group(function () {

            Route::livewire('/', 'pages::expense.index')
                ->name('index');

            Route::livewire('/create', 'pages::expense.form')
                ->middleware('permission:expense.create')
                ->name('create');

            Route::livewire('/{expense}/edit', 'pages::expense.form')
                ->middleware('permission:expense.update')
                ->name('edit');
        });

    // ITEM PURCHASE MANAGEMENT
    Route::prefix('item-purchases')
        ->name('item-purchases.')
        ->middleware(['permission:item-purchase.view'])
        ->group(function () {

            Route::livewire('/', 'pages::item-purchase.index')
                ->name('index');

            Route::livewire('/create', 'pages::item-purchase.form')
                ->middleware('permission:item-purchase.create')
                ->name('create');

            Route::livewire('/{itemPurchase}/edit', 'pages::item-purchase.form')
                ->middleware('permission:item-purchase.update')
                ->name('edit');
        });

    // STOCK ADJUSTMENT MANAGEMENT
    Route::prefix('stock-adjustments')
        ->name('stock-adjustments.')
        ->middleware(['permission:stock-adjustment.view'])
        ->group(function () {

            Route::livewire('/', 'pages::stock-adjustment.index')
                ->name('index');

            Route::livewire('/create', 'pages::stock-adjustment.form')
                ->middleware('permission:stock-adjustment.create')
                ->name('create');

            Route::livewire('/{stockAdjustment}/edit', 'pages::stock-adjustment.form')
                ->middleware('permission:stock-adjustment.update')
                ->name('edit');
        });
});

require __DIR__.'/settings.php';
