<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSearch;

use App\Enums\FiscalCode;

class PaymentType extends Model
{
    use HasSearch;
    
    protected $fillable = [
        'name',
        'fiscal_code_key'
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

    /*
    |--------------------------------------------------------------------------------------------
    | Enum labels
    |--------------------------------------------------------------------------------------------
    */
    public function getFiscalCodeTextAttribute(): ?string
    {
        return FiscalCode::label($this->fiscal_code_key);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Enum keys
    |--------------------------------------------------------------------------------------------
    */
    public static function fiscalCodeKeys(): array
    {
        return FiscalCode::keys();
    }


}
