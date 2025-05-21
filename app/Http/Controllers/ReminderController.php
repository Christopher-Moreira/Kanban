<?php
namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\ImportedData;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:dados_excel,id',
            'reminder_date' => 'required|date',
            'notes' => 'required|string'
        ]);

        $reminder = Reminder::create([
            'imported_data_id' => $validated['contract_id'],
            'reminder_date' => $validated['reminder_date'],
            'notes' => $validated['notes'],
            'is_completed' => false
        ]);

        // Verificar se é uma requisição AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true, 
                'reminder' => $reminder,
                'message' => 'Lembrete adicionado com sucesso!'
            ]);
        }

        return back()->with('success', 'Lembrete adicionado com sucesso!');
    }

    public function update(Request $request, Reminder $reminder)
    {
        $validated = $request->validate([
            'reminder_date' => 'required|date',
            'notes' => 'required|string',
            'is_completed' => 'boolean'
        ]);

        $reminder->update($validated);

        // Verificar se é uma requisição AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true, 
                'reminder' => $reminder,
                'message' => 'Lembrete atualizado com sucesso!'
            ]);
        }

        return back()->with('success', 'Lembrete atualizado com sucesso!');
    }

    public function destroy(Reminder $reminder)
    {
        $reminder->delete();

        // Verificar se é uma requisição AJAX
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lembrete removido com sucesso!'
            ]);
        }

        return back()->with('success', 'Lembrete removido com sucesso!');
    }
    
    public function checkUpdates($contractId)
    {
        $lastUpdate = request('last_update', 0);
        $timestamp = Carbon::now()->timestamp;
        
        $reminders = Reminder::where('imported_data_id', $contractId)
                          ->where('updated_at', '>', Carbon::createFromTimestamp($lastUpdate))
                          ->orderBy('reminder_date')
                          ->get();
        
        if ($reminders->count() > 0) {
            $view = view('partials.reminders', ['reminders' => $reminders])->render();
            
            return response()->json([
                'updated' => true,
                'html' => $view,
                'timestamp' => $timestamp
            ]);
        }
        
        return response()->json([
            'updated' => false,
            'timestamp' => $timestamp
        ]);
    }
}