<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Traits\HasSearch;

class Invoice extends Model
{
    use HasSearch;
    
    protected $fillable = [
        'is_cancelled',
        'number',
        'order_id',
        'year',
        'type_key',
        'item_group_key',
        'business_space_id',
        'business_device_id',
        'customer_name',
        'customer_oib',
        'customer_address',
        'customer_postal',
        'customer_city',
        'customer_phone',
        'customer_email',
        'issued_by',
        'issued_at',
        'due_at',
        'shipping_date'
    ];

    protected $casts = [
        'is_cancelled' => 'boolean'
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

    public function canceller()
    {
        return $this->hasOne(self::class);
    }

    public function kprItem()
    {
        return $this->hasOne(KprItemList::class);
    }

    public function businessSpace()
    {
        return $this->belongsTo(BusinessSpace::class);
    }

    public function businessDevice()
    {
        return $this->belongsTo(BusinessDevice::class);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------------------------
    */
    public function getFormattedIssuedDateAttribute()
    {
        return $this->issued_at
            ? Carbon::parse($this->issued_at)->format('d.m.Y')
            : null;
    }

    public function getFormattedIssuedTimeAttribute()
    {
        return $this->issued_at
            ? Carbon::parse($this->issued_at)->format('H:i')
            : null;
    }

    public function getFormattedShippingDateAttribute()
    {
        return $this->shipping_date
            ? Carbon::parse($this->shipping_date)->format('d.m.Y')
            : null;
    }

    public function getFormattedDueDateAttribute()
    {
        return $this->due_at
            ? Carbon::parse($this->due_at)->format('d.m.Y')
            : null;
    }

    public function getHasPaymentAttribute(): bool
    {
        return $this->kprItem()->exists();
    }

    public function getPaymentIdAttribute(): ?int
    {
        return $this->kprItem()->value('kpr_id');
    }
    
    public function getTypeTextAttribute(): string
    {
        return config('mappings.invoice_types')[$this->type_key] ?? $this->type_key;
    }

    public function getItemGroupTextAttribute(): string
    {
        return config('mappings.item_groups')[$this->item_group_key] ?? $this->item_group_key;
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------------------------
    */
    public static function types(): array
    {
        return config('mappings.invoice_types');
    }

    public static function typeKeys(): array
    {
        return array_keys(self::types());
    }

    public static function itemGroups(): array
    {
        return config('mappings.item_groups');
    }

    public static function itemGroupKeys(): array
    {
        return array_keys(self::itemGroups());
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------------------------
    */
    public function setPaidAmountAttribute($value)
    {
        $this->attributes['paid_amount'] = is_null($value) ? null : str_replace(',', '.', $value);
    }
}