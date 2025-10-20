<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasSearch
{
    /*
    |--------------------------------------------------------------------------------------------
    | Apply search on multiple columns and optional relationships.
    |--------------------------------------------------------------------------------------------
    */
    
    /**
     * @param Builder $query
     * @param string|null $search
     * @param array $columns
     * @param array $relations ['relation' => ['column1', 'column2']]
     * @return Builder
     */
    public function scopeSearch(Builder $query, ?string $search, array $columns = [], array $relations = [])
    {
        if (!$search) return $query;

        $query->where(function ($q) use ($search, $columns, $relations) {
            // Main model columns
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%{$search}%");
            }

            // Relation columns
            foreach ($relations as $relation => $relColumns) {
                $q->orWhereHas($relation, function ($relQuery) use ($search, $relColumns) {
                    $relQuery->where(function ($nested) use ($search, $relColumns) {
                        foreach ($relColumns as $col) {
                            $nested->orWhere($col, 'like', "%{$search}%");
                        }
                    });
                });
            }
        });

        return $query;
    }
}
