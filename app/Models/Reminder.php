<?php
// app/Models/Reminder.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = [
        'imported_data_id',
        'reminder_date',
        'notes',
        'is_completed'
    ];

    public function contract()
    {
        return $this->belongsTo(ImportedData::class);
    }
    public function importedData()
{
    return $this->belongsTo(ImportedData::class);
}
}