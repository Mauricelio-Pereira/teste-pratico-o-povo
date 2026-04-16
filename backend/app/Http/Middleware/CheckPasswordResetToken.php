<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use App\Models\PasswordResetTokenModel;
use App\Utils\Helpers;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordResetToken
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
        $token = $request->bearerToken();

        if (!Helpers::isNonEmptyString($token)) {
            throw new ApiException('Acesso não autorizado. É necessário enviar o token de autorização junto à requisição.', 401);
        }

        $passwordResetToken = $this->findToken($token);
        if (!$passwordResetToken) {
            throw new ApiException('Acesso não autorizado. Token de autenticação inválido.', 401);
        }

        return $next($request);
    }

    /**
     * Encontrar token de redefinição de senha
     *
     * @param string $token O Token
     *
     * @return PasswordResetTokenModel|null
     */
    private function findToken(string $token): PasswordResetTokenModel|null
    {
        if (strpos($token, '|') === false) {
            return PasswordResetTokenModel::where('token', '=', $token)
                ->first();
        }

        [$id, $token] = explode('|', $token, 2);

        if ($instance = PasswordResetTokenModel::find($id)) {
            return hash_equals($instance->token, hash('sha256', $token))
                ? $instance
                : null;
        }

        return null;
    }
}
