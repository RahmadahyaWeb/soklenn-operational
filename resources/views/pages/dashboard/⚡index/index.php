<?php

use App\Models\Expense;
use App\Models\Income;
use App\Models\Item;
use App\Models\ItemPurchase;
use App\Models\Order;
use App\Models\StockAdjustment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Dashboard')] class extends Component
{
    #[Computed()]
    public function todayOrders()
    {
        return Order::whereDate('created_at', today())
            ->count();
    }

    #[Computed()]
    public function todayIncome()
    {
        return Income::whereDate('transaction_date', today())
            ->sum('amount');
    }

    #[Computed()]
    public function todayExpense()
    {
        return Expense::whereDate('transaction_date', today())
            ->sum('amount');
    }

    #[Computed()]
    public function todayProfit()
    {
        return $this->todayIncome - $this->todayExpense;
    }

    #[Computed()]
    public function monthlyIncome()
    {
        return Income::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');
    }

    #[Computed()]
    public function monthlyExpense()
    {
        return Expense::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');
    }

    #[Computed()]
    public function monthlyProfit()
    {
        return $this->monthlyIncome - $this->monthlyExpense;
    }

    #[Computed()]
    public function pendingOrders()
    {
        return Order::where('status', 'pending')->count();
    }

    #[Computed()]
    public function washingOrders()
    {
        return Order::where('status', 'washing')->count();
    }

    #[Computed()]
    public function dryingOrders()
    {
        return Order::where('status', 'drying')->count();
    }

    #[Computed()]
    public function finishedOrders()
    {
        return Order::where('status', 'finished')->count();
    }

    #[Computed()]
    public function pickedUpOrders()
    {
        return Order::where('status', 'picked_up')->count();
    }

    #[Computed()]
    public function recentOrders()
    {
        return Order::with('customer')
            ->latest()
            ->limit(5)
            ->get();
    }

    #[Computed()]
    public function lowStockItems()
    {
        return Item::whereColumn('stock', '<=', 'minimum_stock')
            ->where('is_active', true)
            ->orderBy('stock')
            ->limit(5)
            ->get();
    }

    #[Computed()]
    public function recentStockAdjustments()
    {
        return StockAdjustment::with('item')
            ->latest('adjustment_date')
            ->limit(5)
            ->get();
    }

    #[Computed()]
    public function recentIncomes()
    {
        return Income::latest('transaction_date')
            ->limit(5)
            ->get();
    }

    #[Computed()]
    public function recentExpenses()
    {
        return Expense::latest('transaction_date')
            ->limit(5)
            ->get();
    }

    #[Computed()]
    public function recentPurchases()
    {
        return ItemPurchase::with([
            'supplier',
            'item',
        ])
            ->latest('purchase_date')
            ->limit(5)
            ->get();
    }

    public function statusBadgeColor(string $status): string
    {
        return match ($status) {
            'pending' => 'zinc',
            'washing' => 'blue',
            'drying' => 'yellow',
            'finished' => 'green',
            'picked_up' => 'purple',
            default => 'zinc',
        };
    }
};
