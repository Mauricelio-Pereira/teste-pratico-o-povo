<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PerPageRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return $value === null ||
            (
                is_numeric($value) &&
                (
                    $value == -1 ||
                    (
                        $value > 0 &&
                        strlen((string) $value) <= 3
                    )
                )
            );
    }

    public function message(): string
    {
        return 'O campo :attribute deve ser -1 (Todos) ou um número maior que 0 com no máximo 3 dígitos.';
    }
}
