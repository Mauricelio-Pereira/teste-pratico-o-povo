<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAcceptsJson
{
    /**
     * Handle an incoming request
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->expectsJson()) {
            throw new ApiException('Acesso não autorizado. Somente requisições JSON são aceitas.', 406);
        }

        return $next($request);
    }
}
