<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSearch;

class Product extends Model
{
    use HasSearch;
    
    protected $fillable = [
        'name',
        'group',
        'product_type_id',
        'default_price'
    ];

    /*
    |--------------------------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------------------------
    */
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------------------------
    */
    public function getGroupTextAttribute(): string
    {
        return config('mappings.item_groups')[$this->group] ?? $this->group;
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------------------------
    */
    public static function groups(): array
    {
        return config('mappings.item_groups');
    }

    public static function groupKeys(): array
    {
        return array_keys(self::groups());
    }

    public static function groupFromSearch(string $search): ?string
    {
        $search = mb_strtolower(trim($search));
        $reversed = array_change_key_case(
            array_flip(self::groups()),
            CASE_LOWER
        );

        return $reversed[$search] ?? null;
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------------------------
    */
    public function setDefaultPriceAttribute($value)
    {
        $this->attributes['default_price'] = is_null($value) ? null : str_replace(',', '.', $value);
    }
}
