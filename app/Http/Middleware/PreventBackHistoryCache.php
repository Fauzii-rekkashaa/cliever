<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistoryCache
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hanya tambahkan header kalau response punya method headers
        // (mencegah error kalau response berupa redirect/binary file)
        if (method_exists($response, 'headers')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
        }

        return $response;
    }
}
