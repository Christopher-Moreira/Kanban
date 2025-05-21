<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckReminders extends Command
{
    protected $signature = 'reminders:check';
    protected $description = 'Verifica lembretes pendentes';

    public function handle()
    {
        $now = Carbon::now()->format('Y-m-d H:i:00');
        
        $reminders = Reminder::where('reminder_time', '<=', $now)>where('status', 'pending')->get();

        foreach ($reminders as $reminder) {
            
            $this->info("Lembrete disparado: {$reminder->name}");
            $reminder->update(['status' => 'completed']);
        }
    }
}