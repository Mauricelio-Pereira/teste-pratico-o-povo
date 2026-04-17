<?php

namespace App\Utils;

use App\Http\Resources\PaginationResource;
use Illuminate\Database\Eloquent\Builder;

class Paginator
{
    /**
     * Paginar ou retornar todos os registros de uma query
     *
     * @param Builder $query A query gerada
     * @param int $perPage Número de Linhas por Página
     * @param int|null $page Página
     * @param string $resourceClass O Resource
     * @param callable|null $transformCallback Função para manipular os itens (ex: filtrar ou mapear)
     *
     * @return PaginationResource
     */
    public static function paginateOrAll(
        Builder $query,
        int $perPage,
        int|null $page = null,
        string $resourceClass,
        callable|null $transformCallback = null
    ): PaginationResource {
        if (intval($perPage) === -1) {
            $items = $query->get();

            if ($transformCallback) {
                $items = $transformCallback($items);
            }

            return new PaginationResource([
                'items' => $resourceClass::collection($items),
                'pagination' => null
            ]);
        }

        $paginated = $query->paginate(
            perPage: $perPage,
            page: $page > 0 ? $page + 1 : 0
        );

        $items = $paginated->items();

        if ($transformCallback) {
            $items = $transformCallback(collect($items));
        }

        return new PaginationResource([
            'items' => $resourceClass::collection($paginated),
            'pagination' => $paginated
        ]);
    }
}
