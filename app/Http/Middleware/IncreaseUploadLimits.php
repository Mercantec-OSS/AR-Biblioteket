<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IncreaseUploadLimits
{
    public function handle(Request $request, Closure $next)
    {
        ini_set('upload_max_filesize', '500M');
        ini_set('post_max_size', '500M');
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');
        ini_set('max_input_time', '300');

        return $next($request);
    }
} 