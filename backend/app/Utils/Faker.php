<?php

namespace App\Utils;

use Illuminate\Support\Str;

class Faker
{
    /**
     * Gerar um CNPJ fictício válido e aleatório.
     *
     * @param bool $masked Indica se o CNPJ gerado deve estar adequadamente formatado. Padrão `true`
     *
     * @return string O CNPJ aleatório
     */
    public static function cnpj(bool $masked = true): string
    {
        $baseNumbers = [
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            0,
            0,
            1
        ];

        $firstDigit = self::calculateCNPJDigit($baseNumbers);
        $baseNumbers[] = $firstDigit;
        $secondDigit = self::calculateCNPJDigit($baseNumbers);
        $baseNumbers[] = $secondDigit;

        $format = $masked
            ? '%d%d.%d%d%d.%d%d%d/%d%d%d%d-%d%d'
            : '%d%d%d%d%d%d%d%d%d%d%d%d%d%d';

        return sprintf(
            $format,
            ...$baseNumbers
        );
    }

    /**
     * Gerar um CPF fictício válido e aleatório.
     *
     * @param bool $masked Indica se o CPF gerado deve estar adequadamente formatado. Padrão `true`
     *
     * @return string O CPF aleatório
     */
    public static function cpf(bool $masked = true): string
    {
        $baseNumbers = [
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            rand(0, 9)
        ];

        $firstDigit = self::calculateCPFDigit($baseNumbers);
        $baseNumbers[] = $firstDigit;
        $secondDigit = self::calculateCPFDigit($baseNumbers);
        $baseNumbers[] = $secondDigit;

        $format = $masked
            ? '%d%d%d.%d%d%d.%d%d%d-%d%d'
            : '%d%d%d%d%d%d%d%d%d%d%d';

        return sprintf(
            $format,
            ...$baseNumbers
        );
    }

    /**
     * Gerar um RG fictício e aleatório.
     *
     * @param bool $masked Indica se o RG gerado deve estar adequadamente formatado. Padrão `true`
     *
     * @return string O RG aleatório
     */
    public static function rg(bool $masked = true): string
    {
        $baseNumbers = [
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            rand(0, 9),
            rand(0, 9)
        ];

        $lastDigit = rand(0, 10) === 10 ? 'X' : rand(0, 9);
        $baseNumbers[] = $lastDigit;

        $format = $masked
            ? '%d%d.%d%d%d.%d%d%d-%s'
            : '%d%d%d%d%d%d%d%d%s';

        return sprintf(
            $format,
            ...$baseNumbers
        );
    }

    /**
     * Gerar uma chassi brasileira válida e aleatória.
     *
     * @return string O chassi
     */
    public static function carChassi(): string
    {
        // WMI (World Manufacturer Identifier) - primeiros 3 caracteres
        $wmi = fake()->regexify('[A-HJ-NPR-Z]{3}'); // Fabricante e região

        // VDS (Vehicle Descriptor Section) - próximos 6 caracteres
        $vds = fake()->regexify('[A-HJ-NPR-Z0-9]{6}'); // Detalhes do veículo

        // VIS (Vehicle Identifier Section) - últimos 8 caracteres
        $vis = fake()->regexify('[0-9]{6}[A-HJ-NPR-Z0-9]{2}'); // Identificador único

        // Concatenar as partes para formar o VIN (17 caracteres no total)
        return strtoupper($wmi . $vds . $vis);
    }

    /**
     * Gerar uma RENAVAM brasileira válida e aleatória.
     *
     * @return string O RENAVAM
     */
    public static function carRenavam(): string
    {
        // Gerar os 10 primeiros dígitos aleatórios
        $base = str_pad(random_int(1, 9999999999), 10, '0', STR_PAD_LEFT);

        // Calcular o dígito verificador (módulo 11)
        $sum = 0;
        $multiplier = 2;

        // Iterar os dígitos de trás para frente
        for ($i = 9; $i >= 0; $i--) {
            $sum += $base[$i] * $multiplier;
            $multiplier = $multiplier < 9 ? $multiplier + 1 : 2;
        }

        // Obter o resto da soma dividido por 11
        $remainder = $sum % 11;
        $digit = $remainder < 2 ? 0 : 11 - $remainder;

        // Retornar o RENAVAM completo
        return $base . $digit;
    }

    /**
     * Gerar uma UF brasileira válida e aleatória.
     *
     * @return string A UF brasileira
     */
    public static function uf(): string
    {
        $ufs = [
            'AC',
            'AL',
            'AP',
            'AM',
            'BA',
            'CE',
            'DF',
            'ES',
            'GO',
            'MA',
            'MT',
            'MS',
            'MG',
            'PA',
            'PB',
            'PR',
            'PE',
            'PI',
            'RJ',
            'RN',
            'RS',
            'RO',
            'RR',
            'SC',
            'SP',
            'SE',
            'TO'
        ];

        return $ufs[array_rand($ufs)];
    }

    /**
     * Gerar uma placa de veículo brasileira única e fictícia no padrão Mercosul.
     *
     * @return string A placa de veículo
     */
    public static function licensePlate(): string
    {
        $letters = [
            chr(rand(65, 90)),
            chr(rand(65, 90)),
            chr(rand(65, 90))
        ];

        $numbers = [
            rand(0, 9),
            chr(rand(65, 90)),
            rand(0, 9),
            rand(0, 9)
        ];

        return Str::upper(sprintf(
            '%s%s%s%d%s%d%d',
            $letters[0],
            $letters[1],
            $letters[2],
            $numbers[0],
            $numbers[1],
            $numbers[2],
            $numbers[3]
        ));
    }

    /**
     * buscar as marcas e modelos de veículo.
     *
     * @return array As marcas e modelos
     */
    private static function carBrandAndModel()
    {
        return [
            'Toyota' => ['Corolla', 'Etios', 'Yaris', 'Camry', 'RAV4', 'Prius', 'Highlander', 'Hilux', 'SW4'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'Fit', 'Pilot', 'City'],
            'Ford' => ['Focus', 'Fiesta', 'Mustang', 'Explorer', 'F-150'],
            'Chevrolet' => ['Onix', 'Prisma', 'Celta', 'Malibu', 'Impala', 'Tahoe', 'Silverado', 'Equinox'],
            'BMW' => ['X5', 'X3', 'M3'],
            'Fiat' => ['Uno', 'Palio', 'Strada', 'Toro', 'Mobi', 'Cronos', 'Pulse', 'Argo', 'Ducato'],
            'Volkswagen' => ['Golf', 'Voyage', 'Passat', 'Polo', 'Jetta', 'Tiguan', 'Touareg', 'Gol', 'Arteon', 'Atlas'],
            'Volvo' => ['FH', 'FMX', 'FM'],
            'Scania' => ['R450', 'G360', 'P310'],
            'Mercedes-Benz' => ['Actros', 'Atego', 'Axor'],
            'MAN' => ['TGX', 'TGS', 'TGM'],
            'Iveco' => ['Stralis', 'Tector', 'Hi-Way'],
        ];
    }

    /**
     * Gerar uma marca de veículo.
     *
     * @return string A marca
     */
    public static function carBrand():string
    {
        $carData = self::carBrandAndModel();

        return fake()->randomElement(array_keys($carData));
    }

    /**
     * Gerar uma modelo de veículo.
     *
     * @return string O modelo
     */
    public static function carModel( string | null $brand = null): string
    {
        $carData = self::carBrandAndModel();

        if(!$brand){
            $brand = fake()->randomElement(array_keys($carData));
        }

        return fake()->randomElement($carData[$brand]);
    }

    /**
     * Calcula um dígito verificador do CNPJ.
     *
     * @param array $numbers Os números base do CNPJ (até 12 ou 13 dígitos)
     *
     * @return int Dígito verificador calculado
     */
    private static function calculateCNPJDigit(array $numbers): int
    {
        $weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        if (count($numbers) > 12) {
            $weights = array_merge([6], $weights);
        }

        $sum = 0;
        foreach ($numbers as $i => $number) {
            $sum += $number * $weights[$i];
        }

        $remainder = $sum % 11;

        return $remainder < 2 ? 0 : 11 - $remainder;
    }

    /**
     * Calcula um dígito verificador do CPF.
     *
     * @param array $numbers Os números base do CPF (até 9 ou 10 dígitos)
     *
     * @return int Dígito verificador calculado
     */
    private static function calculateCPFDigit(array $numbers): int
    {
        $weights = range(count($numbers) + 1, 2);
        $sum = 0;

        foreach ($numbers as $i => $number) {
            $sum += $number * $weights[$i];
        }

        $remainder = $sum % 11;

        return $remainder < 2 ? 0 : 11 - $remainder;
    }
}
