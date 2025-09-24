<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\GlobalService;

class Customer extends Model
{

    protected $fillable = [
        'name',
        'oib',
        'email',
        'phone',
        'address',
        'house_number',
        'city',
        'postal',
        'country_id'
    ];

    /*
    |--------------------------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------------------------
    */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------------------------
    */
    public function getFormattedTotalOrderedAmountAttribute()
    {
        if (!$this->relationLoaded('orders')) {
        return 'Nema narudžbi';
        }

        $total = 0;

        foreach ($this->orders as $order) {
            $total += GlobalService::sumOrderItems(orderId: $order->id);
        }

        return number_format($total, 2, ',', '.');
    }

}
