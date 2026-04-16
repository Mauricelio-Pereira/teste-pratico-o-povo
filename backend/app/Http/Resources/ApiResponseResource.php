<?php

namespace App\Http\Resources;

use App\Utils\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $code O código de resposta HTTP
 * @property JsonResource|null $data Os dados da resposta
 * @property array<string, mixed>|null $debug Detalhes do erro de resposta
 * @property string $msg A mensagem de resposta
 * @property bool $ok Booleano indicando se a requisição da API foi bem-sucedida
 */
class ApiResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $code = $this->resolveProperty('code');
        $data = $this->resolveProperty('data');
        $debug = $this->resolveProperty('debug');
        $msg = $this->resolveProperty('msg');
        $ok = $this->resolveProperty('ok');

        return [
            'code' => $this->when(
                Helpers::isHttpCode($code),
                $code,
                500
            ),
            'data' => $this->when(
                $data instanceof JsonResource,
                $data
            ),
            'debug' => $this->when(
                Helpers::isNonEmptyObject($debug) || Helpers::isNonEmptyArray($debug),
                $debug
            ),
            'msg' => $this->when(
                Helpers::isNonEmptyString($msg),
                $msg,
                'Ocorreu um erro ao processar a sua solicitação!'
            ),
            'ok' => $this->when(
                is_bool($ok),
                $ok,
                false
            )
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     */
    public function withResponse(Request $request, JsonResponse $response)
    {
        $code = $this->resolveProperty('code');
        $code = Helpers::isHttpCode($code)
            ? $code
            : 500;

        $jsonHeaders = [
            'Content-Type' => 'application/json',
            'Charset' => 'UTF-8',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
        ];

        $encodingOptions = JSON_UNESCAPED_UNICODE;
        if (app()->environment('local')) {
            $encodingOptions |= JSON_PRETTY_PRINT;
        }

        $response
            ->withHeaders($jsonHeaders)
            ->setEncodingOptions($encodingOptions)
            ->setStatusCode($code);
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
