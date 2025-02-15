<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryService extends Model
{
    // change default table name
    protected $table = 'delivery_service';

    // Fillable for checkbox
    protected $fillable = ['in_use'];
    protected $casts = ['in_use' => 'boolean'];
    
    public function setDefaultCostAttribute($value)
    {
        $this->attributes['default_cost'] = is_null($value) ? null : str_replace(',', '.', $value);

    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function deliveryCompany()
    {
        return $this->belongsTo(DeliveryCompany::class, 'delivery_company_id');
    }

}
