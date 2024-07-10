<?php

namespace App\Filters\Components;

use Closure;

class Receipt
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['receipt_no'])) {
            $content['model'] = $content['model']->where('receipt_no', $content['params']['receipt_no']);
        }

        return $next($content);
    }
}
