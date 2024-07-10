<?php

namespace App\Filters\Components;

use Closure;

class CategoryFilter
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['category'])) {
            $content['model'] = $content['model']->where('category_id', $content['params']['category']);
        }

        return $next($content);
    }
}
