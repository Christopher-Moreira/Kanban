<?php
namespace App\Console\Commands;

use App\Models\ImportedData;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateNotifications extends Command
{
    protected $signature = 'notifications:generate';
    protected $description = 'Gera notificações baseadas nos contratos';

    public function handle()
    {
       
        ImportedData::where('dias_atraso_parcela', '>', 0)->each(function($card) {
            $triggerDate = now()->addDays(3); // -> Dias para notificar //Mudar para Atribuição UserSide no banco
            
            Notification::updateOrCreate(
                [
                    'card_id' => $card->id,
                    'type' => 'alerta_vencimento'
                ],
                [
                    'message' => "Contrato {$card->contrato} próximo do vencimento!",
                    'trigger_date' => $triggerDate,
                    'expiration_date' => $triggerDate->addDays(2)
                ]
            );
        });

        $this->info('Notificações geradas com sucesso!');
    }
}