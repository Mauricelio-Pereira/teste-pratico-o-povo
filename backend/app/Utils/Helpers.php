<?php

namespace App\Utils;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class Helpers
{
    /**
     * Verificar se um valor é uma string não vazia.
     *
     * @param mixed $value O valor a ser verificado
     *
     * @return bool Um `booleano` indicando se o valor é uma string não vazia
     */
    public static function isNonEmptyString(mixed $value): bool
    {
        return is_string($value) && trim($value) !== '';
    }

    /**
     * Verificar se um valor é um número não vazio.
     * Essa função verifica números inteiros e decimais e considera 0 como um número válido.
     *
     * @param mixed $value O valor a ser verificado
     *
     * @return bool Um `booleano` indicando se o valor é um número não vazio
     */
    public static function isNonEmptyNumber(mixed $value): bool
    {
        return (is_int($value) || is_float($value)) && $value !== null;
    }

    /**
     * Verificar se um valor é um array não vazia.
     *
     * @param mixed $value O valor a ser verificado
     *
     * @return bool Um `booleano` indicando se o valor é um array não vazia
     */
    public static function isNonEmptyArray(mixed $value): bool
    {
        return is_array($value) && !empty($value);
    }

    /**
     * Verificar se um valor é um objeto não vazio.
     *
     * @param mixed $value O valor a ser verificado
     *
     * @return bool Um `booleano` indicando se o valor é um objeto não vazio
     */
    public static function isNonEmptyObject(mixed $value): bool
    {
        return is_object($value) && !empty((array) $value);
    }

    /**
     * Verificar se um valor é um código HTTP válido.
     *
     * @param mixed $code O valor a ser verificado
     *
     * @return bool Um `booleano` indicando se o valor é um código HTTP válido.
     */
    public static function isHttpCode(mixed $code): bool
    {
        $statusCode = [
            100,
            101,
            200,
            201,
            202,
            203,
            204,
            205,
            206,
            300,
            301,
            302,
            303,
            304,
            305,
            306,
            307,
            400,
            401,
            402,
            403,
            404,
            405,
            406,
            407,
            408,
            409,
            410,
            411,
            412,
            413,
            414,
            415,
            416,
            417,
            500,
            501,
            502,
            503,
            504,
            505
        ];

        return is_int($code) && in_array($code, $statusCode);
    }

    /**
     * Mascarar um de e-mail para manter a privacidade
     *
     * @param string $email O e-mail
     *
     * @return string|null O e-mail mascarado
     */
    public static function maskEmail(string $email): string|null
    {
        [$name, $domain] = explode('@', $email);

        $nameLength = strlen($name);
        $maskLength = max(1, floor($nameLength / 2));

        $maskedName = substr($name, 0, 3)
            . str_repeat('*', $maskLength)
            . substr($name, $nameLength - 3, 3);

        return "$maskedName@$domain";
    }

    /**
     * Gerar token aleatório.
     *
     * **IMPORTANTE:** Se este token for salvo em um banco de dados, é aconselhável codificá-lo por questões de segurança
     *
     * @return string O token gerado
     */
    public static function generateRandomToken(): string
    {
        return sprintf(
            '%s%s%s',
            config('sanctum.token_prefix', ''),
            $tokenEntropy = Str::random(40),
            hash('crc32b', $tokenEntropy)
        );
    }

    /**
     * Corrige o nome do arquivo, removendo acentos, espaços e caracteres especiais.
     *
     * @param string $fileName Nome original do arquivo.
     * @return string Nome corrigido e seguro.
     */
    public static function fixFileName(string $fileName): string
    {
        // Substitui espaços e underscores por hífens
        $fileName = str_replace([' ', '_'], '-', $fileName);

        // Remove acentuação
        $fileName = iconv('UTF-8', 'ASCII//TRANSLIT', $fileName);

        // Remove caracteres especiais indesejados (mantém letras, números, ponto e hífen)
        $fileName = preg_replace('/[^A-Za-z0-9\.\-]/', '', $fileName);

        // Converte para minúsculas
        $fileName = strtolower($fileName);

        // Remove hífens duplicados ou no início/fim
        $fileName = trim(preg_replace('/-+/', '-', $fileName), '-');

        return $fileName;
    }

    /**
     * Remover acentos em palavras.
     *
     * @param string $string As palavras.
     *
     * @return string Retorna as palavras sem acentos.
     */
    public static function removeAccents($string)
    {
        if (!$string) {
            return $string;
        }

        $accents = array(
            'Á' => 'A',
            'À' => 'A',
            'Â' => 'A',
            'Ã' => 'A',
            'Ä' => 'A',
            'Å' => 'A',
            'á' => 'a',
            'à' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'ä' => 'a',
            'å' => 'a',
            'É' => 'E',
            'È' => 'E',
            'Ê' => 'E',
            'Ë' => 'E',
            'é' => 'e',
            'è' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'Í' => 'I',
            'Ì' => 'I',
            'Î' => 'I',
            'Ï' => 'I',
            'í' => 'i',
            'ì' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'Ó' => 'O',
            'Ò' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ö' => 'O',
            'ó' => 'o',
            'ò' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ö' => 'o',
            'Ú' => 'U',
            'Ù' => 'U',
            'Û' => 'U',
            'Ü' => 'U',
            'ú' => 'u',
            'ù' => 'u',
            'û' => 'u',
            'ü' => 'u',
            'Ç' => 'C',
            'ç' => 'c',
            'Ñ' => 'N',
            'ñ' => 'n'
        );

        return strtr($string, $accents);
    }

    /**
     * Buscar código do estado pela tabela do IBGE.
     *
     * @param string $siglaEstado a sigla do estado.
     *
     * @return string Retorna o código do estado.
     */
    public static function getCodeState($siglaEstado)
    {
        // Array de mapeamento: siglas dos estados => código IBGE
        $estados = array(
            "AC" => "12",
            "AL" => "27",
            "AP" => "16",
            "AM" => "13",
            "BA" => "29",
            "CE" => "23",
            "DF" => "53",
            "ES" => "32",
            "GO" => "52",
            "MA" => "21",
            "MT" => "51",
            "MS" => "50",
            "MG" => "31",
            "PA" => "15",
            "PB" => "25",
            "PR" => "41",
            "PE" => "26",
            "PI" => "22",
            "RJ" => "33",
            "RN" => "24",
            "RS" => "43",
            "RO" => "11",
            "RR" => "14",
            "SC" => "42",
            "SP" => "35",
            "SE" => "28",
            "TO" => "17"
        );

        // Converte a sigla para maiúsculas para garantir a correspondência
        $siglaEstado = strtoupper($siglaEstado);

        // Verifica se a sigla existe no array e retorna o código correspondente
        if (array_key_exists($siglaEstado, $estados)) {
            return $estados[$siglaEstado];
        } else {
            return '';
        }
    }
}
