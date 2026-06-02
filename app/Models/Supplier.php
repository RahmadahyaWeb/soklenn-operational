<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
    ];

    public function itemPurchases()
    {
        return $this->hasMany(ItemPurchase::class);
    }
}
