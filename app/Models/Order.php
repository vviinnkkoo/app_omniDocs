<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    // replace dot with comma for display
    public function getDeliveryWeightAttribute($value)
    {
        return str_replace('.', ',', $value);
    }

    // replace dot with comma for display
    public function setDeliveryWeightAttribute($value)
    {
        $this->attributes['delivery_weight'] = is_null($value) ? null : str_replace(',', '.', $value);

    }

    public function receipt(): HasOne
    {
        return $this->hasOne(Receipt::class);
    }

    public function orderItemList()
    {
        return $this->hasMany(OrderItemList::class);
    }

    public function isOrderDone()
{
    return $this->orderItemList->every(function ($item) {
        return $item->is_done == 1;
    });
}
}
