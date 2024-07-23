<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConvertRequestFieldsToSnakeCase
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $replaced = [];
        foreach ($request->all() as $key => $value) {
            $replaced[Str::snake($key)] = $value;
        }
        $request->replace($replaced);

        return $next($request);
    }
}
