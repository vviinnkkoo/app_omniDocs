<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $casts = [
        'date_cancelled' => 'datetime',
        'date_delivered' => 'datetime',
        'date_ordered' => 'datetime',
        'date_sent' => 'datetime',
        'date_deadline' => 'datetime'
    ];

    protected $fillable = [
        'date_ordered',
        'date_deadline',
        'customer_id',
        'source_id',
        'delivery_service_id',
        'payment_type_id',
        'delivery_address',
        'delivery_city',
        'delivery_country_id',
        'delivery_postal',
        'delivery_phone',
        'delivery_email',
    ];

    /*
    |--------------------------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------------------------
    */
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
        return $this->belongsTo(Country::class, 'delivery_country_id');
    }

    public function isOrderDone()
    {
        return $this->orderItemList->every(function ($item) {
            return $item->is_done == 1;
        });
    }

    /*
    |--------------------------------------------------------------------------------------------
    | GET accessors
    |--------------------------------------------------------------------------------------------
    */
    public function getPaymentTypeNameAttribute()
    {
        return $this->paymentType->name ?? null;
    }

    public function getCountryNameAttribute()
    {
        return $this->country->name ?? null;
    }

    public function getCustomerNameAttribute()
    {
        return $this->customer->name ?? null;
    }

    public function getSourceNameAttribute()
    {
        return $this->source->name ?? null;
    }

    public function getDeliveryServiceNameAttribute()
    {
        return $this->deliveryService->name ?? null;
    }

    public function getDeliveryCompanyNameAttribute()
    {
        return $this->deliveryService->deliveryCompany->name ?? null;
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Date formatters (for DISPLAY purposes)
    |--------------------------------------------------------------------------------------------
    */
    public function getFormatedDateSentAttribute()
    {
        return $this->date_sent ? $this->date_sent->format('d.m.Y.') : null;
    }

    public function getFormatedDateOrderedAttribute()
    {
        return $this->date_ordered ? $this->date_ordered->format('d.m.Y.') : null;
    }

    public function getFormatedDateDeadlineAttribute()
    {
        return $this->date_deadline ? $this->date_deadline->format('d.m.Y.') : null;
    }

    public function getFormatedDateDeliveredAttribute()
    {
        return $this->date_delivered ? $this->date_delivered->format('d.m.Y.') : null;
    }

    public function getFormatedDateCancelledAttribute()
    {
        return $this->date_cancelled ? $this->date_cancelled->format('d.m.Y.') : null;
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Date formatters (for DATE INPUT purposes)
    |--------------------------------------------------------------------------------------------
    */
    public function getInputFormatedDateSentAttribute()
    {
        return $this->date_sent ? $this->date_sent->format('Y-m-d') : null;
    }

    public function getInputFormatedDateOrderedAttribute()
    {
        return $this->date_ordered ? $this->date_ordered->format('Y-m-d') : null;
    }

    public function getInputFormatedDateDeadlineAttribute()
    {
        return $this->date_deadline ? $this->date_deadline->format('Y-m-d') : null;
    }

    public function getInputFormatedDateDeliveredAttribute()
    {
        return $this->date_delivered ? $this->date_delivered->format('Y-m-d') : null;
    }

    public function getInputFormatedDateCancelledAttribute()
    {
        return $this->date_cancelled ? $this->date_cancelled->format('Y-m-d') : null;
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Calculate days to deliver, left and deadlines, deadline color class
    |--------------------------------------------------------------------------------------------
    */
    public function getDaysToDeliverAttribute()
    {
        return $this->date_delivered ? $this->date_delivered->diffInDays($this->date_ordered) : null;
    }

    public function getDaysLeftAttribute()
    {
        if (!$this->date_deadline || $this->date_deadline->isPast()) {
            return null;
        }

        return now()->diffInDays($this->date_deadline);
    }

    public function getDeadlineClassAttribute()
    {
        if ($this->days_left <= 5) {
            return 'btn-danger';
        } elseif ($this->days_left <= 10) {
            return 'btn-warning';
        }

        return 'btn-success';
    }

}
