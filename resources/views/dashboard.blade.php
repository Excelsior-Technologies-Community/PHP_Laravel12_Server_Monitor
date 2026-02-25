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