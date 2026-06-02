<!DOCTYPE html>
<html>
<head>
    <title>Server Monitor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center">
        <h2>🚀 Server Monitor Dashboard</h2>
        <span id="refresh-indicator" class="badge bg-secondary">Auto-refreshing...</span>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card bg-success text-white p-3">
                <h4>Total Servers</h4>
                <h2 id="total-stat">{{ $total }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white p-3">
                <h4>UP</h4>
                <h2 id="up-stat">{{ $up }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white p-3">
                <h4>DOWN</h4>
                <h2 id="down-stat">{{ $down }}</h2>
            </div>
        </div>
    </div>

    <table class="table table-bordered mt-4 bg-white">
        <thead>
        <tr>
            <th>Name</th>
            <th>IP / Port</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody id="servers-table-body">
        @foreach($servers as $server)
            <tr id="server-row-{{ $server->id }}">
                <td>{{ $server->name }}</td>
                <td>{{ $server->ip ?? '127.0.0.1' }}:{{ $server->port ?? 80 }}</td>
                <td class="status-cell">
                    @if($server->live_status === 'up')
                        <span class="badge bg-success">UP</span>
                    @else
                        <span class="badge bg-danger">DOWN</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="row mt-4" id="charts-container">
        @foreach($servers as $server)
            <div class="col-md-6 mb-4">
                <div class="card p-3 shadow-sm">
                    <h5>{{ $server->name }} Performance (ms)</h5>
                    <div style="height: 220px;">
                        <canvas id="chart-{{ $server->id }}"></canvas>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <h4 class="mt-4">Recent Logs</h4>
    <table class="table table-striped bg-white">
        <thead>
        <tr>
            <th>Server</th>
            <th>Status</th>
            <th>Time</th>
        </tr>
        </thead>
        <tbody id="logs-table-body">
        @foreach($logs as $log)
        <tr>
            <td>{{ $log->server_name }}</td>
            <td>
                <span class="badge {{ $log->status === 'up' ? 'bg-success' : 'bg-danger' }}">
                    {{ $log->status }}
                </span>
            </td>
            <td>{{ $log->checked_at }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>

</div>

<script>
    const charts = {};

    function initChart(id, labels, data) {
        const ctx = document.getElementById(`chart-${id}`);
        if (!ctx) return;

        charts[id] = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Response Time (ms)',
                    data: data,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    async function fetchDashboardData() {
        try {
            const response = await fetch('/api/server-status');
            const data = await response.json();

            let total = data.servers.length;
            let up = 0;
            let down = 0;

            data.servers.forEach(server => {
                if (server.live_status === 'up') {
                    up++;
                } else {
                    down++;
                }

                const row = document.getElementById(`server-row-${server.id}`);
                if (row) {
                    const statusCell = row.querySelector('.status-cell');
                    if (server.live_status === 'up') {
                        statusCell.innerHTML = '<span class="badge bg-success">UP</span>';
                    } else {
                        statusCell.innerHTML = '<span class="badge bg-danger">DOWN</span>';
                    }
                }

                const chartInfo = data.charts[server.id];
                if (chartInfo) {
                    if (charts[server.id]) {
                        charts[server.id].data.labels = chartInfo.labels;
                        charts[server.id].data.datasets.data = chartInfo.data;
                        charts[server.id].update();
                    } else {
                        initChart(server.id, chartInfo.labels, chartInfo.data);
                    }
                }
            });

            document.getElementById('total-stat').textContent = total;
            document.getElementById('up-stat').textContent = up;
            document.getElementById('down-stat').textContent = down;

        } catch (error) {
            console.error(error);
        }
    }

    window.addEventListener('load', () => {
        fetchDashboardData();
        setInterval(fetchDashboardData, 10000);
    });
</script>

</body>
</html>