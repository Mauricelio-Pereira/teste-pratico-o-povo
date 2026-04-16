<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ApiException extends Exception
{
    /**
     * @param string $message The exception message
     * @param int|null $code The exception code
     * @param Throwable|null $previous The previous exception used for the exception chaining
     */
    public function __construct(
        string $message,
        int|null $code = null,
        Throwable|null $previous = null
    ) {
        parent::__construct($message, $code ?? 0, $previous);
    }
}
