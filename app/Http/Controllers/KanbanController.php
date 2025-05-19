<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportedData;
use Illuminate\Database\Eloquent\Model;


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

        $dados = ImportedData::all(); // Pega todos os dados da tabela

        return view('layouts.kanban', compact('dados'));

    }
}