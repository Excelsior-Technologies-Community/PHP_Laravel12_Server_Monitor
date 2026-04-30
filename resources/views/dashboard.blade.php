<!DOCTYPE html>
<html>
<head>
    <title>Server Monitor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>

<div class="container mt-4">

    <h2>🚀 Server Monitor Dashboard</h2>

    <!-- STATS -->
    <div class="row mt-4">

        <div class="col-md-4">
            <div class="card bg-success text-white p-3">
                <h4>Total Servers</h4>
                <h2>{{ $total }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-primary text-white p-3">
                <h4>UP</h4>
                <h2>{{ $up }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-danger text-white p-3">
                <h4>DOWN</h4>
                <h2>{{ $down }}</h2>
            </div>
        </div>

    </div>

    <!-- SERVER TABLE -->
    <table class="table table-bordered mt-4">
        <thead>
        <tr>
            <th>Name</th>
            <th>IP</th>
            <th>Status</th>
        </tr>
        </thead>

        <tbody>
        @foreach($servers as $server)
            <tr>
                <td>{{ $server->name }}</td>
                <td>{{ $server->host }}</td>
                <td>
                    @if($server->isHealthy())
                        <span class="text-success">UP</span>
                    @else
                        <span class="text-danger">DOWN</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- LOGS -->
    <h4>Recent Logs</h4>
    <table class="table">
        <tr>
            <th>Server</th>
            <th>Status</th>
            <th>Time</th>
        </tr>

        @foreach($logs as $log)
        <tr>
            <td>{{ $log->server_name }}</td>
            <td>{{ $log->status }}</td>
            <td>{{ $log->checked_at }}</td>
        </tr>
        @endforeach
    </table>

</div>

</body>
</html>