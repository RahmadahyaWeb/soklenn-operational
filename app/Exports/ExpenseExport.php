<?php

namespace App\Exports;

use App\Models\Expense;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpenseExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(
        protected ?string $search = null,
        protected ?string $expenseCategoryId = null,
        protected ?string $startDate = null,
        protected ?string $endDate = null,
    ) {}

    public function collection(): Collection
    {
        return Expense::with([
            'category',
            'itemPurchase.supplier',
        ])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%'.$this->search.'%');
            })
            ->when($this->expenseCategoryId, function ($query) {
                $query->where('expense_category_id', $this->expenseCategoryId);
            })
            ->when($this->startDate, function ($query) {
                $query->whereDate('transaction_date', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                $query->whereDate('transaction_date', '<=', $this->endDate);
            })
            ->latest('transaction_date')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Title',
            'Category',
            'Supplier',
            'Amount',
            'Created At',
        ];
    }

    public function map($expense): array
    {
        return [
            optional($expense->transaction_date)->format('Y-m-d'),
            $expense->title,
            $expense->category?->name,
            $expense->itemPurchase?->supplier?->name,
            $expense->amount,
            optional($expense->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
