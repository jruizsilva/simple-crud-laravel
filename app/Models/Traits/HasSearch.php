<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasSearch
{
    private function searchableFields()
    {
        return [];
    }

    public function scopeSearch(Builder $builder, $search)
    {
        if (empty($search))
            return $builder;

        $builder->where(function (Builder $builder) use ($search) {
            $wordsToSearch = explode(" ", $search);
            foreach ($wordsToSearch as $word) {
                $builder->whereAny($this->searchableFields(), 'like', "%$word%");
            };
        });

        return $builder;
    }
}