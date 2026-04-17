<?php

namespace App\Http\Requests\Post;

use App\Models\Post;
use App\Rules\PerPageRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            'perPage' => 'Número de Linhas por Página',
            'page' => 'Página',
            'id' => 'ID do Blog',
            'title' => 'Título do Blog',
            'content' => 'Conteúdo do Blog',
            'createdAt' => 'Data de criação do Blog',
            'createdAt.start' => 'Data de criação inicial do blog',
            'createdAt.end' => 'Data de criação fim do blog'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $postTable = (new Post())->getTable();

        return [
            'perPage' => [
                'nullable',
                'numeric',
                new PerPageRule
            ],
            'page' => [
                'nullable',
                'numeric',
                'min_digits:1',
                'max_digits:3'
            ],
            'id' => [
                'nullable',
                'numeric',
                "exists:$postTable,id"
            ],
            'title' => [
                'nullable',
                'string',
                'max:255'
            ],
            'content' => [
                'nullable',
                'string'
            ],
            'createdAt' => [
                'nullable',
                'array',
            ],
            'createdAt.start' => [
                'nullable',
                'date',
            ],
            'createdAt.end' => [
                'nullable',
                'date',
            ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id.exists' => 'O ":attribute" informado não existe'
        ];
    }
}
