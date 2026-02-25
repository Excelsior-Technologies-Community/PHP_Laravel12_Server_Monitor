<?php

namespace App\Http\Controllers;

use Spatie\ServerMonitor\Models\Host; // <-- Use Host, 

class ServerController extends Controller
{
    public function index()
    {
        $servers = Host::all(); // <-- replaced Server::all() with Host::all()
        return view('dashboard', compact('servers'));
    }
}