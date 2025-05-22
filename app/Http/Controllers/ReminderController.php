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
                'contract_id' => $validated['contract_id'],
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

    public function complete(Request $request, Reminder $reminder)
    {
        $reminder->update([
            'is_completed' => true,
            'completed_at' => Carbon::now()
        ]);

        // Verificar se é uma requisição AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'reminder' => $reminder,
                'message' => 'Lembrete marcado como concluído!'
            ]);
        }

        return back()->with('success', 'Lembrete marcado como concluído!');
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
                          ->where('is_completed', false)
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

    public function getUpcomingReminders()
    {
        $now = Carbon::now();
        $twoDaysFromNow = $now->copy()->addDays(2);

        $upcomingReminders = Reminder::with(['importedData'])
            ->where('is_completed', false)
            ->whereBetween('reminder_date', [$now, $twoDaysFromNow])
            ->orderBy('reminder_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'reminders' => $upcomingReminders,
            'count' => $upcomingReminders->count()
        ]);
    }

    public function getOverdueReminders()
    {
        $now = Carbon::now();

        $overdueReminders = Reminder::with(['importedData'])
            ->where('is_completed', false)
            ->where('reminder_date', '<', $now)
            ->orderBy('reminder_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'reminders' => $overdueReminders,
            'count' => $overdueReminders->count()
        ]);
    }

    public function bulkComplete(Request $request)
    {
        $validated = $request->validate([
            'reminder_ids' => 'required|array',
            'reminder_ids.*' => 'exists:reminders,id'
        ]);

        $updatedCount = Reminder::whereIn('id', $validated['reminder_ids'])
            ->update([
                'is_completed' => true,
                'completed_at' => Carbon::now()
            ]);

        return response()->json([
            'success' => true,
            'message' => "{$updatedCount} lembretes marcados como concluídos!",
            'updated_count' => $updatedCount
        ]);
    }

    public function snooze(Request $request, Reminder $reminder)
    {
        $validated = $request->validate([
            'snooze_minutes' => 'required|integer|min:15|max:1440' // 15 minutos a 24 horas
        ]);

        $newReminderDate = Carbon::parse($reminder->reminder_date)
            ->addMinutes($validated['snooze_minutes']);

        $reminder->update([
            'reminder_date' => $newReminderDate
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'reminder' => $reminder,
                'message' => 'Lembrete adiado com sucesso!',
                'new_date' => $newReminderDate->format('d/m/Y H:i')
            ]);
        }

        return back()->with('success', 'Lembrete adiado com sucesso!');
    }
}