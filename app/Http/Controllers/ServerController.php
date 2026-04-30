<?php

namespace App\Http\Controllers;

use Spatie\ServerMonitor\Models\Host;
use App\Models\ServerLog;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Host::all();

        $total = $servers->count();
        $up = $servers->filter(fn($s) => $s->isHealthy())->count();
        $down = $total - $up;

        $logs = ServerLog::latest()->take(10)->get();

        return view('dashboard', compact('servers', 'total', 'up', 'down', 'logs'));
    }
}