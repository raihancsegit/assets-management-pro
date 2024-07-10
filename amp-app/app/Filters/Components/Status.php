<?php

namespace App\Filters\Components;

use Closure;

class Status
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['status'])) {
            $content['model'] = $content['model']->where('status', $content['params']['status']);
        }

        return $next($content);
    }
}
