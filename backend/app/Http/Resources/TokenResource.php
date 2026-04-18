<?php

namespace App\Http\Resources;

use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

/**
 * @property string $text O token
 * @property string $expiresAt A data de expiração do token
 * @property UserResource $user O usuário autenticado
 */
class TokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $text = $this->resolveProperty('text');
        $expiresAt = $this->resolveProperty('expiresAt');
        $user = $this->resolveProperty('user');

        return [
            'token' => [
                'text' => $this->when(
                    Helpers::isNonEmptyString($text),
                    $text,
                ),
                'expiresAt' => $this->when(
                    Helpers::isNonEmptyString($expiresAt),
                    $expiresAt,
                )
            ],
            'user' => $this->when(
                $user instanceof UserResource,
                $user,
            ),
        ];
    }

    /**
     * Resolver uma propriedade que pode ser uma chave de array ou uma propriedade de objeto
     *
     * @param string $property A propriedade
     *
     * @return mixed
     */
    private function resolveProperty(string $property)
    {
        return $this->{$property}
            ?? $this->resource[$property]
            ?? null;
    }
}
