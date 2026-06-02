<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\Item;
use App\Models\ItemPurchase;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Service;
use App\Models\StockAdjustment;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categories = [
            [
                'name' => 'Chemical',
                'type' => 'operational',
            ],
            [
                'name' => 'Cleaning Equipment',
                'type' => 'operational',
            ],
            [
                'name' => 'Packaging',
                'type' => 'operational',
            ],
            [
                'name' => 'Office Equipment',
                'type' => 'non_operational',
            ],
        ];

        foreach ($categories as $category) {

            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Suppliers
        |--------------------------------------------------------------------------
        */

        $suppliers = [
            [
                'name' => 'PT Sneaker Supply',
                'phone' => '081234567890',
                'address' => 'Jakarta',
            ],
            [
                'name' => 'Clean Shoes Indonesia',
                'phone' => '081234567891',
                'address' => 'Bandung',
            ],
        ];

        foreach ($suppliers as $supplier) {

            Supplier::firstOrCreate(
                ['name' => $supplier['name']],
                $supplier
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Services
        |--------------------------------------------------------------------------
        */

        $services = [
            [
                'name' => 'Deep Clean',
                'price' => 50000,
                'description' => 'Deep cleaning service',
                'is_active' => true,
            ],
            [
                'name' => 'Fast Clean',
                'price' => 30000,
                'description' => 'Fast cleaning service',
                'is_active' => true,
            ],
            [
                'name' => 'Repaint',
                'price' => 150000,
                'description' => 'Shoes repaint service',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {

            Service::firstOrCreate(
                ['name' => $service['name']],
                $service
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Customers
        |--------------------------------------------------------------------------
        */

        $customers = [
            [
                'name' => 'Rahmad',
                'phone' => '081111111111',
            ],
            [
                'name' => 'Aulia',
                'phone' => '082222222222',
            ],
            [
                'name' => 'Fahmi',
                'phone' => '083333333333',
            ],
        ];

        foreach ($customers as $customer) {

            Customer::firstOrCreate(
                ['phone' => $customer['phone']],
                $customer
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Expense Categories
        |--------------------------------------------------------------------------
        */

        $expenseCategories = [
            'Operational',
            'Electricity',
            'Water',
            'Internet',
            'Salary',
        ];

        foreach ($expenseCategories as $category) {

            ExpenseCategory::firstOrCreate([
                'name' => $category,
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Items
        |--------------------------------------------------------------------------
        */

        $chemical = Category::where('name', 'Chemical')->first();

        $equipment = Category::where('name', 'Cleaning Equipment')->first();

        $items = [
            [
                'category_id' => $chemical->id,
                'name' => 'Sneaker Shampoo',
                'unit' => 'bottle',
                'stock' => 10,
                'minimum_stock' => 5,
                'buy_price' => 50000,
                'description' => 'Sneaker cleaning shampoo',
                'is_active' => true,
            ],
            [
                'category_id' => $equipment->id,
                'name' => 'Soft Brush',
                'unit' => 'pcs',
                'stock' => 15,
                'minimum_stock' => 5,
                'buy_price' => 25000,
                'description' => 'Soft cleaning brush',
                'is_active' => true,
            ],
            [
                'category_id' => $chemical->id,
                'name' => 'Foam Cleaner',
                'unit' => 'bottle',
                'stock' => 3,
                'minimum_stock' => 5,
                'buy_price' => 45000,
                'description' => 'Foam cleaner liquid',
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {

            Item::firstOrCreate(
                ['name' => $item['name']],
                $item
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Item Purchases
        |--------------------------------------------------------------------------
        */

        $supplier = Supplier::first();

        $item = Item::where('name', 'Sneaker Shampoo')->first();

        $purchase = ItemPurchase::create([
            'item_id' => $item->id,
            'supplier_id' => $supplier->id,
            'purchase_date' => now()->toDateString(),
            'qty' => 5,
            'price' => 50000,
            'total' => 250000,
            'note' => 'Restock shampoo',
        ]);

        $item->increment('stock', 5);

        /*
        |--------------------------------------------------------------------------
        | Automatic Expense From Purchase
        |--------------------------------------------------------------------------
        */

        $expenseCategory = ExpenseCategory::where('name', 'Operational')
            ->first();

        Expense::create([
            'expense_category_id' => $expenseCategory->id,
            'item_purchase_id' => $purchase->id,
            'transaction_date' => now()->toDateString(),
            'amount' => 250000,
            'title' => 'Purchase - '.$item->name,
            'description' => 'Automatic expense from purchase',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Stock Adjustments
        |--------------------------------------------------------------------------
        */

        $beforeStock = $item->stock;

        $afterStock = $beforeStock - 2;

        $item->update([
            'stock' => $afterStock,
        ]);

        StockAdjustment::create([
            'item_id' => $item->id,
            'type' => 'out',
            'qty' => 2,
            'before_stock' => $beforeStock,
            'after_stock' => $afterStock,
            'note' => 'Damaged item',
            'adjustment_date' => now()->toDateString(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Orders
        |--------------------------------------------------------------------------
        */

        $customer = Customer::first();

        $service = Service::first();

        $order = Order::create([
            'customer_id' => $customer->id,
            'invoice_number' => 'INV-'.now()->format('YmdHis'),
            'received_at' => now(),
            'completed_at' => now()->addDays(2),
            'status' => 'picked_up',
            'subtotal' => 50000,
            'discount' => 0,
            'grand_total' => 50000,
            'note' => 'Customer requested fast process',
        ]);

        OrderDetail::create([
            'order_id' => $order->id,
            'service_id' => $service->id,
            'qty' => 1,
            'price' => 50000,
            'total' => 50000,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Automatic Income From Order
        |--------------------------------------------------------------------------
        */

        $incomeCategory = IncomeCategory::first();

        Income::create([
            'income_category_id' => $incomeCategory->id,
            'order_id' => $order->id,
            'transaction_date' => now()->toDateString(),
            'amount' => $order->grand_total,
            'title' => 'Income from Order '.$order->invoice_number,
            'description' => 'Automatic income from order',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Manual Expenses
        |--------------------------------------------------------------------------
        */

        $electricity = ExpenseCategory::where('name', 'Electricity')
            ->first();

        Expense::create([
            'expense_category_id' => $electricity->id,
            'transaction_date' => now()->toDateString(),
            'amount' => 350000,
            'title' => 'Monthly Electricity Bill',
            'description' => 'Operational electricity expense',
        ]);

        $internet = ExpenseCategory::where('name', 'Internet')
            ->first();

        Expense::create([
            'expense_category_id' => $internet->id,
            'transaction_date' => now()->toDateString(),
            'amount' => 250000,
            'title' => 'Internet Subscription',
            'description' => 'Monthly internet expense',
        ]);
    }
}
