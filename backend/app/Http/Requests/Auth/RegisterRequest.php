<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class RegisterRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nome do Usuário',
            'email' => 'E-mail do Usuário',
            'password' => 'Senha Provisória',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userTable = (new User())->getTable();

        return [
            'name' => [
                'required',
                'string',
                'max:256'
            ],
            'email' => [
                'required',
                'string',
                'max:256',
                'email',
                "unique:$userTable,email"
            ],
            'password' => [
                'required',
                'string',
                'max:256',
                Password::defaults()
            ],
        ];
    }
}
