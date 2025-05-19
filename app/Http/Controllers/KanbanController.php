<?php

namespace App\Http\Controllers;

use App\Models\ImportedData;
use Illuminate\Http\Request;

class KanbanController extends Controller
{
    public function index()
    {
        // Agrupar os dados por status
        $cards = ImportedData::all()->groupBy('status');
        
        // Definir os status possíveis que queremos exibir
        $statusColumns = [
            'Pendente',
            'Em Andamento',
            'Concluído',
            'Atrasado'
        ];
        
        return view('kanban.index', compact('cards', 'statusColumns'));
    }
    
    public function update(Request $request)
    {
        $cardId = $request->input('card_id');
        $newStatus = $request->input('status');
        
        $card = ImportedData::findOrFail($cardId);
        $card->status = $newStatus;
        $card->save();
        
        return response()->json(['success' => true]);
    }
    
    public function show($id)
    {
        $card = ImportedData::findOrFail($id);
        return view('kanban.show', compact('card'));
    }
}