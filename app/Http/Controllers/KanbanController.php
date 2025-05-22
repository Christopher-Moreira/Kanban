<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportedData;
use App\Models\Reminder;
use App\Models\NotificationController;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KanbanController extends Controller
{
    public function index()
    {
        // Agrupar os dados por status
        $cards = ImportedData::with('reminders')->get()->groupBy('status');
        
        // Buscar todos os lembretes ativos para a coluna de lembretes
        $allReminders = Reminder::with('importedData')
            ->where('is_completed', false)
            ->orderBy('reminder_date', 'asc')
            ->get();
        
        // Classificar lembretes por urgência
        $now = Carbon::now();
        $remindersByUrgency = [
            'vencidos' => [],
            'proximos' => [], // 2 dias ou menos
            'normais' => []
        ];
        
        foreach ($allReminders as $reminder) {
            $reminderDate = Carbon::parse($reminder->reminder_date);
            $diffInDays = $now->diffInDays($reminderDate, false);
            
            if ($diffInDays < 0) {
                // Vencidos (data já passou)
                $reminder->urgency_type = 'vencido';
                $reminder->days_diff = abs($diffInDays);
                $remindersByUrgency['vencidos'][] = $reminder;
            } elseif ($diffInDays <= 2) {
                // Próximos ao vencimento (2 dias ou menos)
                $reminder->urgency_type = 'proximo';
                $reminder->days_diff = $diffInDays;
                $remindersByUrgency['proximos'][] = $reminder;
            } else {
                // Normais
                $reminder->urgency_type = 'normal';
                $reminder->days_diff = $diffInDays;
                $remindersByUrgency['normais'][] = $reminder;
            }
        }
        
        // Definir os status possíveis que queremos exibir
        $statusColumns = [
            'Pendente',
            'Em Andamento',
            'Concluído',
            'Atrasado',
            'Lembretes'
        ];
        
        return view('kanban.index', compact('cards', 'statusColumns', 'allReminders', 'remindersByUrgency'));
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
        
        return view('kanban.idea', compact('cards', 'statusColumns'));
    }
}