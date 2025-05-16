<?php

namespace App\Http\Controllers;

use App\Models\ImportedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KanbanController extends Controller
{
    /**
     * Exibe a página do quadro Kanban com os dados
     */
    public function index()
    {
        // Obter os dados para o Kanban divididos por status
        $data = [
            'aguardando' => $this->getCardsByStatus('aguardando'),
            'atendendo' => $this->getCardsByStatus('atendendo'),
            'realizado' => $this->getCardsByStatus('realizado')
        ];
        
        return view('kanban.index', compact('data'));
    }
    
    /**
     * Obtém os cartões de acordo com o status
     */
    private function getCardsByStatus($status)
    {
        // Consulta base para obter os dados
        $query = ImportedData::select(
            'id',
            'cliente as name',
            'cpf_cnpj',
            'contrato',
            'saldo_devedor_cont as contractValue',
            'saldo_ad_cc as installmentValue',
            'dias_atraso_parcela as priority',
            'R as responsible',
            'created_at as taskDate',
            'status'
        );
        
        // Definir o status apropriado com base nas regras de negócio
        switch ($status) {
            case 'aguardando':
                // Registros sem atendimento ou com status aguardando
                return $query->where(function($q) {
                    $q->whereNull('status')->orWhere('status', 'aguardando');
                })->get();
                
            case 'atendendo':
                // Registros em andamento/sendo atendidos
                return $query->where('status', 'atendendo')->get();
                
            case 'realizado':
                // Registros concluídos
                return $query->where('status', 'realizado')->get();
                
            default:
                return collect();
        }
    }
    
    /**
     * Atualiza o status de uma tarefa (via AJAX)
     */
    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:dados_excel,id',
            'status' => 'required|in:aguardando,atendendo,realizado'
        ]);
        
        $record = ImportedData::findOrFail($validated['id']);
        $record->status = $validated['status'];
        $record->save();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Adiciona uma nova tarefa ao quadro Kanban
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'taskName' => 'required|string|max:255',
            'contractValue' => 'nullable|numeric',
            'installmentValue' => 'nullable|numeric',
            'taskPriority' => 'required|string|in:high,medium,low',
            'taskResponsible' => 'nullable|string|max:10',
            'taskDate' => 'required|date',
            'cpf_cnpj' => 'nullable|string|max:18',
            'contrato' => 'nullable|string|max:50'
        ]);
        
        // Converter prioridade para dias de atraso
        $diasAtraso = match($validated['taskPriority']) {
            'high' => 90,
            'medium' => 31,
            'low' => 60,
            default => 31
        };
        
        // Criar novo registro
        $record = new ImportedData();
        $record->cliente = $validated['taskName'];
        $record->cpf_cnpj = $validated['cpf_cnpj'] ?? null;
        $record->contrato = $validated['contrato'] ?? null;
        $record->saldo_devedor_cont = $validated['contractValue'] ?? 0;
        $record->saldo_ad_cc = $validated['installmentValue'] ?? 0;
        $record->dias_atraso_parcela = $diasAtraso;
        $record->R = $validated['taskResponsible'];
        $record->created_at = $validated['taskDate'];
        $record->status = 'aguardando';
        $record->save();
        
        return response()->json([
            'success' => true,
            'record' => [
                'id' => $record->id,
                'name' => $record->cliente,
                'contractValue' => number_format($record->saldo_devedor_cont, 2, ',', '.'),
                'installmentValue' => number_format($record->saldo_ad_cc, 2, ',', '.'),
                'priority' => $diasAtraso,
                'responsible' => $record->R,
                'taskDate' => $record->created_at,
                'status' => $record->status
            ]
        ]);
    }
    
    /**
     * Obtém detalhes de uma tarefa específica
     */
    public function show($id)
    {
        $record = ImportedData::findOrFail($id);
        
        return response()->json([
            'id' => $record->id,
            'name' => $record->cliente,
            'cpf_cnpj' => $record->cpf_cnpj,
            'contrato' => $record->contrato,
            'contractValue' => number_format($record->saldo_devedor_cont, 2, ',', '.'),
            'installmentValue' => number_format($record->saldo_ad_cc, 2, ',', '.'),
            'priority' => $record->dias_atraso_parcela,
            'responsible' => $record->R,
            'taskDate' => $record->created_at,
            'status' => $record->status,
            'pa' => $record->pa,
            'transic' => $record->transic,
            'nivel_clas' => $record->nivel_clas,
            'mod_produto' => $record->mod_produto,
            'saldo_devedor_cred' => number_format($record->saldo_devedor_cred, 2, ',', '.')
        ]);
    }
    
    /**
     * Remove uma tarefa do quadro Kanban
     */
    public function destroy($id)
    {
        $record = ImportedData::findOrFail($id);
        $record->delete();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Obtém estatísticas do quadro Kanban
     */
    public function getStatistics()
    {
        $statistics = [
            'total' => ImportedData::count(),
            'aguardando' => ImportedData::where(function($q) {
                $q->whereNull('status')->orWhere('status', 'aguardando');
            })->count(),
            'atendendo' => ImportedData::where('status', 'atendendo')->count(),
            'realizado' => ImportedData::where('status', 'realizado')->count(),
            'valor_total' => ImportedData::sum('saldo_devedor_cont')
        ];
        
        return response()->json($statistics);
    }
}