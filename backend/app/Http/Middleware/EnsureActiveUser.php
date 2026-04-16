<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User */
        $user = $request->user();

        if (!$user) {
            throw new ApiException('Usuário não autenticado!', 401);
        }

        return $next($request);
    }
}
