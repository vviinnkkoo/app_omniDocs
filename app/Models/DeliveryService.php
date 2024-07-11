<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryService extends Model
{
    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */

    // change default table name
    protected $table = 'delivery_service';

    // fillable for checkbox
    protected $fillable = ['in_use'];
    protected $casts = ['in_use' => 'boolean'];

    // replace dot with comma for display
    public function getDefaultCostAttribute($value)
    {
        return str_replace('.', ',', $value);
    }

    // replace dot with comma for display
    public function setDefaultCostAttribute($value)
    {
        //$this->attributes['default_cost'] = str_replace(',', '.', $value);

        // Handle cases where $value is null
        $this->attributes['default_cost'] = is_null($value) ? null : str_replace(',', '.', $value);

    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

}
