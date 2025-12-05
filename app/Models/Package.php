<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSearch;

class Package extends Model
{
    use HasSearch;
    
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

    /*
    |--------------------------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------------------------
    */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryService()
    {
        return $this->belongsTo(DeliveryService::class);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------------------------
    */
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
