<?php

namespace App\Imports;

use App\Models\ImportedData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;


class DataImport implements ToModel, WithHeadingRow, WithValidation
{
    private $rowCount = 0;

    public function model(array $row)
{
    $this->rowCount++;
    
    $normalizedRow = $this->normalizeRow($row);

    return new ImportedData([
        'pa' => $normalizedRow['pa'] ?? null,
        'transic' => $normalizedRow['transic'] ?? null,
        'nivel_clas' => $normalizedRow['nivel_clas'] ?? null,
        'cpf_cnpj' => $normalizedRow['cpf_cnpj'] ?? null,
        'cliente' => $normalizedRow['cliente'] ?? null,
        'contrato' => $normalizedRow['contrato'] ?? null,
        'dias_atraso_parc' => $normalizedRow['dias_atraso_parc'] ?? null,
        'dias_atraso_fin_mes' => $normalizedRow['dias_atraso_a_fin_mes'] ?? null,
        'mod_produto' => $normalizedRow['mod_produto'] ?? null,
        'saldo_dev_cont' => $this->parseDecimal($normalizedRow['saldo_dev_cont'] ?? null),
        'saldo_dev_cred' => $this->parseDecimal($normalizedRow['saldo_dev_cred'] ?? null),
        'saldo_ad_cc' => $this->parseDecimal($normalizedRow['saldo_ad_cc'] ?? null),
        'r' => $normalizedRow['r'] ?? null,
    ]);
}

    private function normalizeRow(array $row): array
    {
        $normalized = [];
        foreach ($row as $key => $value) {
            $normalizedKey = Str::slug(str_replace('.', '', $key), '_');
            $normalized[$normalizedKey] = $value;
        }
        return $normalized;
    }

    private function parseDecimal($value): float
    {
        if (is_string($value)) {
            return (float) str_replace(['.', ','], ['', '.'], $value);
        }
        return (float) $value;
    }

    public function rules(): array
    {
        return [
            'pa' => 'nullable|integer',
            'transic' => 'nullable|string',
            'nivel_clas' => 'nullable|string',
            'cpf_cnpj' => 'nullable|string',
            'cliente' => 'nullable|string',
            'contrato' => 'nullable|string',
            'dias_atraso_parc' => 'nullable|integer',
            'dias_atraso_a_fin_mes' => 'nullable|integer',
            'mod_produto' => 'nullable|string',
            'saldo_dev_cont' => 'nullable|numeric',
            'saldo_dev_cred' => 'nullable|numeric',
            'saldo_ad_cc' => 'nullable|numeric',
            'r' => 'nullable|string',
        ];
    }
    
    public function customValidationMessages()
    {
        return [
            // Remove all required messages since everything is optional
            '*.integer' => 'O valor deve ser um número inteiro',
            '*.numeric' => 'O valor deve ser numérico',
            '*.string' => 'O valor deve ser texto',
        ];
    }


    public function headingRow(): int
    {
        return 1;
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}