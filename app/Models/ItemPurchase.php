<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPurchase extends Model
{
    protected $fillable = [
        'item_id',
        'supplier_id',
        'purchase_date',
        'qty',
        'price',
        'total',
        'note',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function expense()
    {
        return $this->hasOne(Expense::class);
    }
}
