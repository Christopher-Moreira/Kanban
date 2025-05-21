<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportedData;
use App\Models\NotificationController;
use Illuminate\Database\Eloquent\Model;

class KanbanController extends Controller
{
    public function index()
    {
        // Agrupar os dados por status
        $cards = ImportedData::with('reminders')->get()->groupBy('status');
        
        // Definir os status possíveis que queremos exibir
        $statusColumns = [
            'Pendente',
            'Em Andamento',
            'Concluído',
            'Atrasado'
        ];
        //Noticações
        //$notifications = Notification::active()->with('card')->get();
        
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
        $card = ImportedData::with('reminders')->findOrFail($id);
        return view('kanban.show', compact('card'));
    }
    
    public function indexTwo()
    {
        // Agrupar os dados por status
        $cards = ImportedData::with('reminders')->get()->groupBy('status');
        
        // Definir os status possíveis que queremos exibir
        $statusColumns = [
            'Pendente',
            'Em Andamento',
            'Concluído',
            'Atrasado'
        ];
        //Noticações
        //$notifications = Notification::active()->with('card')->get();
        
        return view('kanban.idea', compact('cards', 'statusColumns'));
    }
}