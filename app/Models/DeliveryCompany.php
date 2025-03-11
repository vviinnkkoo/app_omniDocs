<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCompany extends Model
{
    use HasFactory;

    // change default table name
    protected $table = 'delivery_company';

    public function deliveryServices()
    {
        return $this->hasMany(DeliveryService::class, 'delivery_company_id');
    }
}
