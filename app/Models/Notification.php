<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'card_id', 
        'type', 
        'message', 
        'trigger_date',
        'expiration_date'
    ];

    public function card()
    {
        return $this->belongsTo(ImportedData::class, 'card_id');
    }

    // Escopo para notificações ativas
    public function scopeActive($query)
    {
        return $query->where('trigger_date', '<=', now())
            ->where(function($q) {
                $q->where('expiration_date', '>=', now())
                  ->orWhereNull('expiration_date');
            });
    }
}