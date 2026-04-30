# PHP_Laravel12_Server_Monitor

## Introduction

PHP_Laravel12_Server_Monitor is a Laravel 12-based server health monitoring application built using the powerful Spatie Laravel Server Monitor package.

This project demonstrates how to monitor server resources such as disk space, database connectivity, and system services directly from a Laravel application.

It provides:

- Real-time server health checks

- CLI-based monitoring

- Web dashboard display

- Database-driven check tracking

- Extendable monitoring structure

---

## Project Overview

PHP_Laravel12_Server_Monitor is a Laravel 12-based web application designed to monitor server health and system resources using the Spatie Laravel Server Monitor package.

The project integrates Laravel’s MVC architecture with a powerful monitoring package to track server status, store check results in a database, and display server health information through a simple web dashboard.

---

## Technologies Used

- Laravel 12

- PHP

- MySQL

- Spatie Laravel Server Monitor

- Bootstrap 5

- Artisan CLI

---

## Step 1: Create Laravel 12 Project

Open terminal and run:

```bash
composer create-project laravel/laravel PHP_Laravel12_Server_Monitor "12.*"
cd PHP_Laravel12_Server_Monitor
```

This will create a fresh Laravel 12 project.

---

## Step 2: configure .env

```.env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=server_monitor
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:

```bash
php artisan migrate
```
---

## Step 3: Install Server Monitor Package

Install the Spatie package:

```bash
composer require spatie/laravel-server-monitor
```

Publish the configuration and migration files:

```bash
php artisan vendor:publish --provider="Spatie\ServerMonitor\ServerMonitorServiceProvider" --tag="config"
php artisan vendor:publish --provider="Spatie\ServerMonitor\ServerMonitorServiceProvider" --tag="migrations"
```

Run migrations:

```bash
php artisan migrate
```

---

## Step 4: Configure Server Monitor

Edit `config/server-monitor.php` to add servers you want to monitor:

```php
'servers' => [
    [
        'name' => 'localhost',
        'host' => '127.0.0.1',
        'port' => 22,
        'user' => 'root',
        'sudo' => true,
    ],
],
```

---

## Step 5: Create a Controller

Create a controller to display monitored servers:

```bash
php artisan make:controller ServerController
```

`app/Http/Controllers/ServerController.php`:

```php
<?php

namespace App\Http\Controllers;

use Spatie\ServerMonitor\Models\Host; // <-- Use Host, 

class ServerController extends Controller
{
    public function index()
    {
        $servers = Host::all(); 
        return view('dashboard', compact('servers'));
    }
}
```

---

## Step 6: Add Routes

`routes/web.php`:

```php
use App\Http\Controllers\ServerController;

Route::get('/dashboard', [ServerController::class, 'index'])->name('dashboard');
```

---

## Step 7: Create Dashboard View

`resources/views/dashboard.blade.php`:

```html
<!DOCTYPE html>
<html>

<head>
    <title>Server Monitor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Server Monitor Dashboard</h1>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Server Name</th>
                    <th>IP Address</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servers as $server)
                <tr>
                    <td>{{ $server->name }}</td>
                    <td>{{ $server->host ?? '127.0.0.1' }}</td>
                    <td>
                        @if($server->isHealthy())
                        <span style="color:green;">healthy</span>
                        @else
                        <span style="color:red;">unhealthy</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
```

---

## Step 8: Add Host

```bash
php artisan server-monitor:add-host
```

---

## Step 9: Monitor via CLI

Check server status manually:

```bash
php artisan server-monitor:run
```

---

## Step 10: Run the Project

Start the server:

```bash
php artisan serve
```

Visit: `http://127.0.0.1:8000/dashboard`

---

## Output

<img width="1919" height="1028" alt="Screenshot 2026-02-25 111610" src="https://github.com/user-attachments/assets/cf8d8e08-518d-46f9-a0d2-3380de533e9c" />

---

##  Project Structure

```
PHP_Laravel12_Server_Monitor/
├── app/
│   ├── Console/
│   │   └── Commands/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── ServerController.php
│   │   └── Middleware/
│   └── Models/
├── config/
│   └── server-monitor.php
├── database/
│   └── migrations/
├── resources/
│   └── views/
│       └── dashboard.blade.php
├── routes/
│   └── web.php
├── .env
└── artisan
```

---

### Important Note for Windows Users

Spatie Server Monitor requires SSH to run system checks.

If running on:

- Linux Server → Works perfectly

- VPS → Works perfectly

- Windows XAMPP → Requires SSH setup or custom checks

---

Your PHP_Laravel12_Server_Monitor Project is now ready!
<<<<<<< HEAD


=======
>>>>>>> development
