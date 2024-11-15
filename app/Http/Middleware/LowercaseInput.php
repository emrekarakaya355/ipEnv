<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LowercaseInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Tüm input verilerini küçük harfe dönüştür, CSRF tokeni hariç tut
        $input = $request->all();
        if (isset($input['_token'])) {
            $csrfToken = $input['_token'];
            unset($input['_token']);
            $input = array_map('strtolower', $input);
            $input['_token'] = $csrfToken;
        } else {
            $input = array_map('strtolower', $input);
        }

        $request->merge($input);
        return $next($request);
    }
}
