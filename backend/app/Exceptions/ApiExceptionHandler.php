<?php

namespace App\Exceptions;

use App\Http\Resources\ApiResponseResource;
use App\Exceptions\ApiException;
use App\Utils\Helpers;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionHandler
{
    /**
     * Tratar exceções de API e formatar a resposta
     *
     * @param Throwable $exception A exceção capturada
     *
     * @return ApiResponseResource
     */
    public static function handleApiException(Throwable $exception): ApiResponseResource
    {
        $msg = null;
        $code = Helpers::isHttpCode($exception->getCode())
            ? $exception->getCode()
            : 500;

        if (
            $exception instanceof ApiException ||
            $exception instanceof ValidationException ||
            $exception instanceof AuthorizationException
        ) {
            $msg = $exception->getMessage();

            if ($exception instanceof ValidationException) {
                $code = 400;
            } elseif ($exception instanceof AuthorizationException) {
                $code = 401;
            }
        } elseif ($exception instanceof AuthenticationException) {
            $msg = 'Acesso não autorizado. Por favor, faça login para continuar.';
            $code = 401;
        } elseif ($exception instanceof NotFoundHttpException) {
            $msg = 'O caminho que você tentou acessar não existe. Verifique o URL e tente novamente.';
            $code = 404;
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $msg = 'O método utilizado não é permitido!';
            $code = 405;
        } elseif ($exception instanceof FatalError) {
            $msg = 'Ocorreu um erro da nossa parte. Por favor, tente novamente mais tarde!';
        }

        return new ApiResponseResource([
            'code' => $code,
            'debug' => [
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'msg' => optional($exception->getPrevious())
                    ->getMessage() ?? $exception->getMessage(),
            ],
            'msg' => $msg,
        ]);
    }
}
