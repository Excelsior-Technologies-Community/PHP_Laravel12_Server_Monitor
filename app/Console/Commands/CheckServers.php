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
            $startTime = microtime(true);
            $status = 'down';
            $responseTime = 0;

            $port = $server->port ?? 80;
            $ip = $server->ip ?? $server->ip_address;

            if ($ip) {
                $connection = @fsockopen($ip, (int)$port, $errno, $errstr, 2);

                if (is_resource($connection)) {
                    $status = 'up';
                    fclose($connection);
                    $responseTime = round((microtime(true) - $startTime) * 1000);
                }
            }

            if ($status === 'down' && method_exists($server, 'isHealthy')) {
                $status = $server->isHealthy() ? 'up' : 'down';
            }

            ServerLog::create([
                'server_name' => $server->name,
                'status' => $status,
                'message' => $status == 'down' ? 'Server not responding' : 'Server is healthy',
                'checked_at' => now(),
                'response_time' => $responseTime
            ]);

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