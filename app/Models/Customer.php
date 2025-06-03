<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'oib', 'email', 'phone', 'address', 'house_number', 'city', 'postal', 'country_id'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function getFormattedTotalOrderedAmountAttribute()
    {
        if (!$this->relationLoaded('orders')) {
        return 'Nema narudžbi';
        }

        $total = 0;

        foreach ($this->orders as $order) {
            $total += GlobalService::sumWholeOrder($order->id);
        }

        return number_format($total, 2, ',', '.');
    }

}
