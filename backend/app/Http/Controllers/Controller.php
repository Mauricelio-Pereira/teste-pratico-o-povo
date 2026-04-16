<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Utils\Helpers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class Controller
{
    /**
     * @param Request $request
     */
    public function __construct(private Request $request)
    {
    }

    /**
     * Executar uma operação dentro de uma transação de banco de dados.
     *
     * Este método gerencia o início da transação, a execução da operação e a captura de quaisquer exceções que ocorram.
     *
     * @param callable $operation A operação a ser executada.
     * @param string $errMsg A mensagem de erro genérica a ser retornada se ocorrer uma exceção não tratada.
     *
     * @return mixed O resultado da operação
     */
    protected function transaction(
        callable $operation,
        string $errMsg = 'Ocorreu um erro ao processar a sua solicitação!'
    ): mixed {
        DB::beginTransaction();

        try {
            $result = $operation();

            DB::commit();

            return $result;
        } catch (Exception $exception) {
            DB::rollBack();

            $message = $exception instanceof ApiException
                ? $exception->getMessage()
                : $errMsg;
            $code = Helpers::isHttpCode($exception->getCode())
                ? $exception->getCode()
                : 500;

            throw new ApiException(
                $message,
                $code,
                $exception
            );
        }
    }
}
