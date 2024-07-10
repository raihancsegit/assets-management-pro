<?php

namespace App\Filters;

use Illuminate\Pipeline\Pipeline;

class FilterPipeline
{
    public static function forModel($modelClass, $filters)
    {
        return app(Pipeline::class)
            ->send($modelClass::query())
            ->through($filters)
            ->thenReturn();
    }
}
