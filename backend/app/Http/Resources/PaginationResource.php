<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property JsonResource $items Os items da resposta
 * @property \Illuminate\Contracts\Pagination\LengthAwarePaginator $pagination Os dados de paginação da resposta
 */
class PaginationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var JsonResource */
        $items = $this->resolveProperty('items');

        /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator */
        $pagination = $this->resolveProperty('pagination');

        return [
            'items' => $this->when(
                $items instanceof JsonResource,
                $items
            ),
            'pagination' => $this->when(
                $pagination !== null,
                function () use ($pagination) {
                    return [
                        'currentPage' => $pagination->currentPage(),
                        'perPage' => $pagination->perPage(),
                        'total' => $pagination->total(),
                        'lastPage' => $pagination->lastPage(),
                        'nextPageUrl' => $pagination->nextPageUrl(),
                        'previousPageUrl' => $pagination->previousPageUrl(),
                    ];
                }
            )
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
