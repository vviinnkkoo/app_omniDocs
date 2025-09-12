<?php

namespace App\Modules\Packages\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';

    protected $fillable = [
        'order_id',
        'delivery_service_id',
        'status',
        'tracking_number',
        'date_shipped',
        'date_delivered',
        'date_cancelled',
        'weight',
        'cod_price',
        'recipient_name',
        'recipient_address_name',
        'recipient_address_number',
        'recipient_postcode',
        'recipient_city',
        'recipient_country',
        'recipient_country_code',
        'recipient_email',
        'recipient_phone',
    ];

    // Relacije
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function deliveryService()
    {
        return $this->belongsTo(DeliveryService::class, 'delivery_service_id');
    }

    // Accessor za status labelu
    public function getStatusLabelAttribute()
    {
        $labels = [
            'created'   => 'Kreirano',
            'shipped'   => 'Poslano',
            'delivered' => 'Dostavljeno',
            'returned'  => 'Vraćeno',
            'cancelled' => 'Otkazano',
        ];

        return $labels[$this->status] ?? $this->status;
    }
}
