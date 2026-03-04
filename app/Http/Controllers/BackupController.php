<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function index()
    {
        return view("admin.backups.index");
    }

    public function store(Request $request)
    {
        // Lógica para crear un nuevo backup
    }

    public function download($file)
    {
        // Lógica para descargar un backup específico
    }

    public function destroy($file)
    {
        // Lógica para eliminar un backup específico
    }
}
