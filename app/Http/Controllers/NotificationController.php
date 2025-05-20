<?php
namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        return Notification::active()
            ->with('card')
            ->orderBy('trigger_date')
            ->get();
    }
}