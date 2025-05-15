<?php

namespace App\Http\Controllers;

use App\Models\ImportedData;
use Illuminate\Http\Request;

class KanbanController extends Controller
{
    /**
     * Exibe a página Kanban com os dados organizados
     */
    public function index()
    {
        // Busca todos os dados do modelo
        $allData = ImportedData::all();
        
        // Organiza os dados em colunas Kanban baseadas no nível de classificação

        $kanbanColumns = [
            'A' => $allData->where('nivel_clas', 'A'),
            'B' => $allData->where('nivel_clas', 'B'),
            'C' => $allData->where('nivel_clas', 'C'),
            'D' => $allData->where('nivel_clas', 'D'),
            'Outros' => $allData->whereNotIn('nivel_clas', ['A', 'B', 'C', 'D']),
        ];
        
        // Alternativa: agrupar por faixa de dias de atraso
        /*
        $kanbanColumns = [
            'Em dia' => $allData->where('dias_atraso_parcela', '<=', 0),
            '1-30 dias' => $allData->whereBetween('dias_atraso_parcela', [1, 30]),
            '31-60 dias' => $allData->whereBetween('dias_atraso_parcela', [31, 60]),
            '61-90 dias' => $allData->whereBetween('dias_atraso_parcela', [61, 90]),
            '+90 dias' => $allData->where('dias_atraso_parcela', '>', 90),
        ];
        */
        
        return view('kanban.index', compact('kanbanColumns'));
    }

    /**
     * Atualiza o status/nível de um item no Kanban (via AJAX)
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nivel_clas' => 'required|in:A,B,C,D,E,F',
        ]);
        
        $item = ImportedData::findOrFail($id);
        $item->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Item atualizado com sucesso!'
        ]);
    }

    /**
     * Filtra os dados do Kanban com base em critérios
     */
    public function filter(Request $request)
    {
        $query = ImportedData::query();
        
        // Aplicar filtros conforme os parâmetros recebidos
        if ($request->has('pa') && $request->pa) {
            $query->where('pa', 'like', '%' . $request->pa . '%');
        }
        
        if ($request->has('cliente') && $request->cliente) {
            $query->where('cliente', 'like', '%' . $request->cliente . '%');
        }
        
        if ($request->has('dias_atraso_min') && $request->dias_atraso_min) {
            $query->where('dias_atraso_parcela', '>=', $request->dias_atraso_min);
        }
        
        if ($request->has('dias_atraso_max') && $request->dias_atraso_max) {
            $query->where('dias_atraso_parcela', '<=', $request->dias_atraso_max);
        }
        
        $filteredData = $query->get();
        
        // Reorganiza os dados filtrados em colunas Kanban
        $kanbanColumns = [
            'A' => $filteredData->where('nivel_clas', 'A'),
            'B' => $filteredData->where('nivel_clas', 'B'),
            'C' => $filteredData->where('nivel_clas', 'C'),
            'D' => $filteredData->where('nivel_clas', 'D'),
            'Outros' => $filteredData->whereNotIn('nivel_clas', ['A', 'B', 'C', 'D']),
        ];
        
        return view('kanban._kanban_columns', compact('kanbanColumns'))->render();
    }
}