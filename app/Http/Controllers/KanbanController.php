<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportedData;

class KanbanController extends Controller
{
    public function index()
    {
        $dados = ImportedData::all(); // Pega todos os dados da tabela

        return view('layouts.kanban', compact('dados'));
    }
}