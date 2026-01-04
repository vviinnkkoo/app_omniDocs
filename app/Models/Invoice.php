<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Enums\InvoiceType;
use App\Enums\ItemGroup;
use App\Enums\FiscalCode;

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
        'payment_type_id',
        'business_space_id',
        'business_device_id',
        'customer_name',
        'customer_oib',
        'customer_address',
        'customer_postal',
        'customer_city',
        'customer_country',
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

    public function invoiceItemList()
    {
        return $this->hasMany(InvoiceItemList::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | General accessors
    |--------------------------------------------------------------------------------------------
    */
    public function getFormattedIssuedDateAttribute()
    {
        return $this->issued_at ? Carbon::parse($this->issued_at)->format('d.m.Y') : null;
    }

    public function getFormattedIssuedTimeAttribute()
    {
        return $this->issued_at ? Carbon::parse($this->issued_at)->format('H:i') : null;
    }

    public function getFormattedShippingDateAttribute()
    {
        return $this->shipping_date ? Carbon::parse($this->shipping_date)->format('d.m.Y') : null;
    }

    public function getFormattedDueDateAttribute()
    {
        return $this->due_at ? Carbon::parse($this->due_at)->format('d.m.Y') : null;
    }

    public function getHasPaymentAttribute(): bool
    {
        return $this->relationLoaded('kprItem') && $this->kprItem !== null;
    }

    public function getPaymentIdAttribute(): ?int
    {
        return $this->kprItem?->kpr_id;
    }
    
    /*
    |--------------------------------------------------------------------------------------------
    | Enum labels
    |--------------------------------------------------------------------------------------------
    */
    public function getTypeTextAttribute(): string
    {
        return InvoiceType::label($this->type_key);
    }

    public function getItemGroupTextAttribute(): string
    {
        return ItemGroup::label($this->item_group_key);
    }

    public function getFiscalCodeTextAttribute(): string
    {
        return FiscalCode::label($this->paymentType->fiscal_code_key);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Enum keys
    |--------------------------------------------------------------------------------------------
    */
    public static function invoiceTypeKeys(): array
    {
        return InvoiceType::keys();
    }

    public static function itemGroupKeys(): array
    {
        return ItemGroup::keys();
    }

    public static function fiscalCodeKeys(): array
    {
        return FiscalCode::keys();
    }
}