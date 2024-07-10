<?php

namespace App\Filters;

use App\Filters\Components\CategoryFilter;
use App\Filters\Components\DateFilter;
use App\Filters\Components\Status;

class CommonFilter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            CategoryFilter::class,
            DateFilter::class,
            Status::class,
        ];
    }
}
