<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function membership(): HasOne
    {
        return $this->hasOne(CustomerMembership::class);
    }
}
