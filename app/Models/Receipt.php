<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    // fillable for checkbox
    protected $fillable = ['is_cancelled'];
    protected $casts = ['is_cancelled' => 'boolean'];

    // replace dot with comma for display
    public function getPaidAmountAttribute($value)
    {
        return str_replace('.', ',', $value);
    }

    // replace dot with comma for display
    public function setPaidAmountAttribute($value)
    {
        $this->attributes['paid_amount'] = is_null($value) ? null : str_replace(',', '.', $value);

    }

    // Define the relationship to the Order model
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
