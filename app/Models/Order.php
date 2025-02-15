<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
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

    public function orderNote()
    {
        return $this->hasMany(OrderNote::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function deliveryService()
    {
        return $this->belongsTo(DeliveryService::class, 'delivery_service_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'delivery_country_id ');
    }


    public function isOrderDone()
    {
        return $this->orderItemList->every(function ($item) {
            return $item->is_done == 1;
        });
    }
}
