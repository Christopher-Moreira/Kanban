<?php

namespace App\Imports;

use App\Models\ImportedData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new ImportedData([
            'coluna1' => $row['PA'],
        ]);
    }
    
    
    public function headingRow(): int
    {
        return 1; // Normalmente a primeira linha contém os cabeçalhos
    }
}