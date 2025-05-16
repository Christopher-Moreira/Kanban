<?php

namespace App\Imports;

use App\Models\ImportedData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

class DataImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, SkipsOnFailure
{
    use Importable, SkipsFailures;
    
    private $rowCount = 0;
    private $headingMap = [
        'pa' => ['pa'],
        'transic' => ['transic', 'transição', 'transicao'],
        'nivel_clas' => ['nivel clas', 'nivel_clas', 'nivel classificacao', 'nivel_classificacao'],
        'cpf_cnpj' => ['cpf/cnpj', 'cpf_cnpj'],
        'cliente' => ['cliente'],
        'contrato' => ['contrato'],
        'dias_atraso_parc' => ['dias atraso parc', 'dias_atraso_parc'],
        'dias_atraso_fin_mes' => ['dias atraso a/ fin mes', 'dias_atraso_fin_mes', 'dias_atraso_a_fin_mes'],
        'mod_produto' => ['mod produto', 'mod_produto', 'modalidade produto'],
        'saldo_dev_cont' => ['saldo dev cont', 'saldo_dev_cont', 'saldo devedor contrato'],
        'saldo_dev_cred' => ['saldo dev cred', 'saldo_dev_cred', 'saldo devedor credito'],
        'saldo_ad_cc' => ['saldo ad cc', 'saldo_ad_cc'],
        'r' => ['r']
    ];

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
            'dias_atraso_parcela' => $normalizedRow['dias_atraso_parc'] ?? null,
            'dias_atraso_a_fin_mes' => $normalizedRow['dias_atraso_fin_mes'] ?? null,
            'mod_produto' => $normalizedRow['mod_produto'] ?? null,
            'saldo_devedor_cont' => $this->parseDecimal($normalizedRow['saldo_dev_cont'] ?? null),
            'saldo_devedor_cred' => $this->parseDecimal($normalizedRow['saldo_dev_cred'] ?? null),
            'saldo_ad_cc' => $this->parseDecimal($normalizedRow['saldo_ad_cc'] ?? null),
            'R' => $normalizedRow['r'] ?? null,
        ]);
    }

    private function normalizeRow(array $row): array
    {
        $normalized = [];
        
        // Normalizar as chaves e procurar correspondências no headingMap
        foreach ($this->headingMap as $normalizedKey => $possibleKeys) {
            $found = false;
            
            // Verifica cada possível chave para este campo normalizado
            foreach ($possibleKeys as $possibleKey) {
                // Procura por correspondências exatas primeiro
                if (isset($row[$possibleKey])) {
                    $normalized[$normalizedKey] = $row[$possibleKey];
                    $found = true;
                    break;
                }
                
                // Caso não encontre correspondência exata, procura por correspondências insensíveis a caso
                foreach ($row as $key => $value) {
                    if (rcasecmp($key, $possibleKey) === 0 || 
                        strcasecmp(Str::slug(str_replace(['.', ' '], ['', '_'], $key), '_'), $possibleKey) === 0) {
                        $normalized[$normalizedKey] = $value;
                        $found = true;
                        break 2;
                    }
                }
            }
            
            // Se ainda não encontrou, tenta uma abordagem mais flexível
            if (!$found) {
                foreach ($row as $key => $value) {
                    $cleanKey = Str::slug(str_replace(['.', ' '], ['', '_'], $key), '_');
                    
                    foreach ($possibleKeys as $possibleKey) {
                        // Verifica se a chave original ou limpa contém o possibleKey
                        if (stripos($key, $possibleKey) !== false || stripos($cleanKey, $possibleKey) !== false) {
                            $normalized[$normalizedKey] = $value;
                            $found = true;
                            break 2;
                        }
                    }
                }
            }
        }
        
        return $normalized;
    }

    private function parseDecimal($value): ?float
    {
        if ($value === null) {
            return null;
        }
        
        if (is_numeric($value)) {
            return (float) $value;
        }
        
        if (is_string($value)) {
            // Remover qualquer caractere que não seja número, vírgula ou ponto
            $value = preg_replace('/[^\d,.]/', '', $value);
            
            // Tratar formato brasileiro (1.234,56)
            if (strpos($value, ',') !== false) {
                $value = str_replace('.', '', $value); // Remove pontos de milhares
                $value = str_replace(',', '.', $value); // Troca vírgula decimal por ponto
            }
            
            return (float) $value;
        }
        
        return 0.0;
    }

    public function rules(): array
    {
        return [
            '*.pa' => 'nullable',
            '*.transic' => 'nullable',
            '*.nivel_clas' => 'nullable',
            '*.cpf_cnpj' => 'nullable',
            '*.cliente' => 'nullable',
            '*.contrato' => 'nullable',
            '*.dias_atraso_parc' => 'nullable',
            '*.dias_atraso_fin_mes' => 'nullable',
            '*.mod_produto' => 'nullable',
            '*.saldo_dev_cont' => 'nullable',
            '*.saldo_dev_cred' => 'nullable',
            '*.saldo_ad_cc' => 'nullable',
            '*.r' => 'nullable',
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
    
    public function batchSize(): int
    {
        return 100;
    }
    
    public function chunkSize(): int
    {
        return 500;
    }
}