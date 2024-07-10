<?php

namespace App\Filters;

use App\Filters\Components\DateFilter;
use App\Filters\Components\Receipt;
use App\Filters\Components\Status;

class SchemeFilter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            Status::class,
            Receipt::class,
            DateFilter::class,
        ];
    }
}
