<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSearch;

class DeliveryService extends Model
{
    use HasSearch;
    
    protected $fillable = [
        'name',
        'delivery_company_id',
        'default_cost',
        'in_use'
    ];
    
    protected $casts = [
        'in_use' => 'boolean'
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

    public function deliveryCompany()
    {
        return $this->belongsTo(DeliveryCompany::class);
    }
    
    /*
    |--------------------------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------------------------
    */
    public function setDefaultCostAttribute($value)
    {
        $this->attributes['default_cost'] = is_null($value) ? null : str_replace(',', '.', $value);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | General accessors
    |--------------------------------------------------------------------------------------------
    */
    public function getFormatedDefaultCostAttribute()
    {
        $value = $this->attributes['default_cost'];
        return is_null($value) ? null : str_replace('.', ',', $value);
    }
}
