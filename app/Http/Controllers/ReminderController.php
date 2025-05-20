<?php
namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\ImportedData;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'contract_id' => 'required|exists:dados_excel,id',
            'reminder_date' => 'required|date',
            'notes' => 'required|string'
        ]);

        Reminder::create([
            'imported_data_id' => $request->contract_id,
            'reminder_date' => $request->reminder_date,
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Lembrete adicionado com sucesso!');
    }

    public function update(Request $request, Reminder $reminder)
    {
        $reminder->update($request->validate([
            'reminder_date' => 'required|date',
            'notes' => 'required|string',
            'is_completed' => 'boolean'
        ]));

        return back()->with('success', 'Lembrete atualizado!');
    }

    public function destroy(Reminder $reminder)
    {
        $reminder->delete();
        return back()->with('success', 'Lembrete removido!');
    }
}