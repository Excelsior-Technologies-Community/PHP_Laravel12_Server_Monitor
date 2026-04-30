<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\ServerMonitor\Models\Host;
use App\Models\ServerLog;
use Illuminate\Support\Facades\Mail;

class CheckServers extends Command
{
    protected $signature = 'servers:check';
    protected $description = 'Check all servers and store logs';

    public function handle()
    {
        $servers = Host::all();

        foreach ($servers as $server) {

            $status = $server->isHealthy() ? 'up' : 'down';

            // Save log
            ServerLog::create([
                'server_name' => $server->name,
                'status' => $status,
                'message' => $status == 'down' ? 'Server not responding' : 'Server is healthy',
                'checked_at' => now()
            ]);

            // OPTIONAL EMAIL ALERT
            if ($status == 'down') {
                Mail::raw("Server {$server->name} is DOWN!", function ($msg) {
                    $msg->to('admin@example.com')
                        ->subject('Server Down Alert');
                });
            }
        }

        $this->info("Server check completed");
    }
}