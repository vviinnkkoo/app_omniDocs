<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemList extends Model
{
    use HasFactory;

    // change default table name
    protected $table = 'order_item_list';
    // fillable for checkbox
    protected $fillable = ['is_done'];
    protected $casts = ['is_done' => 'boolean'];

    // replace dot with comma for display
    public function getPriceAttribute($value)
    {
        return str_replace('.', ',', $value);
    }

    // replace dot with comma for display
    public function setPriceAttribute($value)
    {
        // Handle cases where $value is null
        $this->attributes['price'] = is_null($value) ? null : str_replace(',', '.', $value);

    }

    public function getAmountAttribute($value)
    {
        return str_replace('.', ',', $value);
    }

    // replace dot with comma for display
    public function setAmountAttribute($value)
    {
        // Handle cases where $value is null
        $this->attributes['amount'] = is_null($value) ? null : str_replace(',', '.', $value);

    }

    // Define the relationship to the Order model
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
