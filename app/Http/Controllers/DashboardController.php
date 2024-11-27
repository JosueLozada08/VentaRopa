<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'message' => 'Bienvenido al Dashboard de Usuario',
        ]);
    }
}
