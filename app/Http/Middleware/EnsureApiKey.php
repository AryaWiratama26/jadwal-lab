<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('X-API-KEY');

        if (! $key && $request->bearerToken()) {
            $key = $request->bearerToken();
        }

        if (! $key) {
            return response()->json([
                'message' => 'Unauthenticated. API key is missing. Provide it via X-API-KEY header.',
            ], 401);
        }

        $apiKey = ApiKey::findByPlaintext($key);

        if (! $apiKey) {
            return response()->json([
                'message' => 'Unauthenticated. Invalid or revoked API key.',
            ], 401);
        }

        $apiKey->markUsed();
        $request->attributes->set('api_key', $apiKey);

        return $next($request);
    }
}
