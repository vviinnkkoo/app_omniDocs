<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCompany extends Model
{
    use HasFactory;

    // change default table name
    protected $table = 'delivery_company';

    public function deliveryService()
    {
        return $this->belongsTo(DeliveryService::class, 'delivery_service_id');
    }
}
