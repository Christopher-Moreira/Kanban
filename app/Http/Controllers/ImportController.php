<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\DataImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    public function showImportForm()
    {
        return view('import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file'=>'required|mimes:xlsx,xls,csv,txt|max:2048'
        ]);

        // Aumentar o tempo limite para 5 minutos
        set_time_limit(300);

        $file = $request->file('file');

        try {
            // Configurar o nível de log para emergência (praticamente desativa os logs)
            $originalLogLevel = Log::getLogger()->getHandlers()[0]->getLevel();
            Log::getLogger()->getHandlers()[0]->setLevel(\Monolog\Logger::EMERGENCY);

            $import = new DataImport();
            
            // Identificar o formato do arquivo
            $extension = $file->getClientOriginalExtension();
            if ($extension == 'csv' || $extension == 'txt') {
                Excel::import($import, $file, null, \Maatwebsite\Excel\Excel::CSV);
            } else {
                Excel::import($import, $file, null, \Maatwebsite\Excel\Excel::XLSX);
            }
            
            $importedRows = $import->getRowCount();

            // Restaurar o nível original de logs
            Log::getLogger()->getHandlers()[0]->setLevel($originalLogLevel);

            $failures = $import->failures();

            if($failures->isNotEmpty()){
                return back()
                    ->with('success',"Importado {$importedRows} linhas com sucesso!")
                    ->with('failures',$failures);
            }

            return back()->with('success',"Dados importados com sucesso! {$importedRows} registros processados.");

        } catch(\Exception $e){
            // Restaurar o nível de logs em caso de erro
            if(isset($originalLogLevel)){
                Log::getLogger()->getHandlers()[0]->setLevel($originalLogLevel);
            }

            Log::error('Import Error: '.$e->getMessage());
            Log::error('File: '.$file->getClientOriginalName());
            Log::error('Trace: '.$e->getTraceAsString());

            $errorMsg = $this->getFriendlyErrorMessage($e);
            return back()->with('error',$errorMsg);
        }
    }

    private function getFriendlyErrorMessage(\Exception $e): string
    {
        return match(true){
            str_contains($e->getMessage(),'preg_match()')=>"Formato de arquivo inválido. Verifique cabeçalhos e valores.",
            str_contains($e->getMessage(),'zip member')=>"Arquivo corrompido. Salve novamente no Excel como .xlsx e tente novamente.",
            str_contains($e->getMessage(),'Undefined array key')=>"Cabeçalhos incorretos. Verifique se as colunas do arquivo correspondem ao modelo esperado.",
            str_contains($e->getMessage(),'Maximum execution time')=>"O arquivo é muito grande. Divida em arquivos menores ou contate o administrador.",
            default=>"Erro ao importar: ".$e->getMessage(),
        };
    }
}