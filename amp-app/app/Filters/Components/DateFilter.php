<?php

namespace App\Filters\Components;

use Closure;

class DateFilter
{
    public function handle(array $content, Closure $next): mixed
    {

        if (isset($content['params']['start_date']) && isset($content['params']['end_date'])) {
            $content['model'] = $content['model']->whereBetween('date', [
                new \DateTime($content['params']['start_date']),
                new \DateTime($content['params']['end_date']),
            ]);
        } elseif (isset($content['params']['start_date'])) {
            $content['model'] = $content['model']->where('date', '>=', $content['params']['start_date']);
        } elseif (isset($content['params']['end_date'])) {
            $content['model'] = $content['model']->where('date', '<=', $content['params']['end_date']);
        }

        return $next($content);
    }
}
