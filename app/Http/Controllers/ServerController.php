<?php

namespace App\Http\Controllers;

use Spatie\ServerMonitor\Models\Host;
use App\Models\ServerLog;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Host::all();

        foreach ($servers as $server) {
            $lastLog = ServerLog::where('server_name', $server->name)->latest('checked_at')->first();
            $server->live_status = $lastLog ? $lastLog->status : 'unknown';
        }

        $total = $servers->count();
        $up = $servers->filter(fn($s) => $s->live_status === 'up')->count();
        $down = $total - $up;

        $logs = ServerLog::latest('checked_at')->take(10)->get();

        return view('dashboard', compact('servers', 'total', 'up', 'down', 'logs'));
    }

    public function getStatusData()
    {
        $servers = Host::all();
        $chartData = [];

        foreach ($servers as $server) {
            $lastLog = ServerLog::where('server_name', $server->name)->latest('checked_at')->first();
            $server->live_status = $lastLog ? $lastLog->status : 'unknown';

            $logs = ServerLog::where('server_name', $server->name)
                ->orderBy('id', 'DESC')
                ->limit(10)
                ->get()
                ->reverse();

            $chartData[$server->id] = [
                'labels' => $logs->map(fn($log) => \Carbon\Carbon::parse($log->checked_at)->format('H:i:s'))->values(),
                'data' => $logs->map(fn($log) => $log->response_time ?? 0)->values(),
            ];
        }

        return response()->json([
            'servers' => $servers,
            'charts' => $chartData
        ]);
    }
}