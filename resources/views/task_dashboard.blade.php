@extends('layouts.app')

@section('title', 'Automation Control Center')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .card-custom {
        background: #ffffff;
        border: none;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .badge-signature {
        background: #f1f5f9;
        color: #334155;
        font-family: monospace;
        font-size: 13px;
        padding: 4px 8px;
        border-radius: 6px;
    }
    .console-box {
        background: #0f172a;
        color: #38bdf8;
        font-family: monospace;
        padding: 15px;
        border-radius: 8px;
        font-size: 13px;
        max-height: 200px;
        overflow-y: auto;
    }
    .chart-box-dimensions {
        position: relative;
        height: 220px;
        width: 100%;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold mb-1">Automation Control Center</h1>
            <p class="text-muted small mb-0">Manage cronless background workers and metric timelines</p>
        </div>
        <button class="btn btn-outline-secondary btn-sm" onclick="window.location.reload()">
            <i class="fas fa-sync-alt"></i> Refresh view
        </button>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card card-custom p-4 h-100">
                <h2 class="h6 font-weight-bold text-secondary mb-3">Active Operational Tasks</h2>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr class="text-uppercase text-muted fs-7">
                                <th>Task Worker Signature</th>
                                <th>Interval Cycle Configuration</th>
                                <th class="text-end">Manual Override Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge-signature">emails:send</span></td>
                                <td>
                                    <select class="form-select form-select-sm select-interval" data-command="emails:send" style="max-width: 180px;">
                                        <option value="everyMinute" {{ isset($settings['emails:send']) && $settings['emails:send']->interval_type == 'everyMinute' ? 'selected' : '' }}>Every Minute</option>
                                        <option value="everyFiveMinutes" {{ isset($settings['emails:send']) && $settings['emails:send']->interval_type == 'everyFiveMinutes' ? 'selected' : '' }}>Every 5 Minutes</option>
                                        <option value="hourly" {{ isset($settings['emails:send']) && $settings['emails:send']->interval_type == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                        <option value="daily" {{ isset($settings['emails:send']) && $settings['emails:send']->interval_type == 'daily' ? 'selected' : '' }}>Daily</option>
                                    </select>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-primary btn-sm btn-run" data-command="emails:send">
                                        <i class="fas fa-play"></i> Trigger Now
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="badge-signature">logs:clean</span></td>
                                <td>
                                    <select class="form-select form-select-sm select-interval" data-command="logs:clean" style="max-width: 180px;">
                                        <option value="everyMinute" {{ isset($settings['logs:clean']) && $settings['logs:clean']->interval_type == 'everyMinute' ? 'selected' : '' }}>Every Minute</option>
                                        <option value="everyFiveMinutes" {{ isset($settings['logs:clean']) && $settings['logs:clean']->interval_type == 'everyFiveMinutes' ? 'selected' : '' }}>Every 5 Minutes</option>
                                        <option value="hourly" {{ isset($settings['logs:clean']) && $settings['logs:clean']->interval_type == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                        <option value="daily" {{ isset($settings['logs:clean']) && $settings['logs:clean']->interval_type == 'daily' ? 'selected' : '' }}>Daily</option>
                                    </select>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-primary btn-sm btn-run" data-command="logs:clean">
                                        <i class="fas fa-play"></i> Trigger Now
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="badge-signature">system:health</span></td>
                                <td>
                                    <select class="form-select form-select-sm select-interval" data-command="system:health" style="max-width: 180px;">
                                        <option value="everyMinute" {{ isset($settings['system:health']) && $settings['system:health']->interval_type == 'everyMinute' ? 'selected' : '' }}>Every Minute</option>
                                        <option value="everyFiveMinutes" {{ isset($settings['system:health']) && $settings['system:health']->interval_type == 'everyFiveMinutes' ? 'selected' : '' }}>Every 5 Minutes</option>
                                        <option value="hourly" {{ isset($settings['system:health']) && $settings['system:health']->interval_type == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                        <option value="daily" {{ isset($settings['system:health']) && $settings['system:health']->interval_type == 'daily' ? 'selected' : '' }}>Daily</option>
                                    </select>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-primary btn-sm btn-run" data-command="system:health">
                                        <i class="fas fa-play"></i> Trigger Now
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-custom p-4 h-100">
                <h2 class="h6 font-weight-bold text-secondary mb-3">Live Allocations Metric</h2>
                <div class="chart-box-dimensions">
                    <canvas id="memoryLineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom p-4 mb-4 d-none" id="consoleOutputPanel">
        <h2 class="h6 font-weight-bold text-secondary mb-2">Real-time Pipeline Terminal Output</h2>
        <div class="console-box" id="consoleOutputBuffer"></div>
    </div>

    <div class="card card-custom p-4">
        <h2 class="h6 font-weight-bold text-secondary mb-3">Task Execution & Activity History</h2>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr class="text-uppercase text-muted fs-7">
                        <th>Worker Action</th>
                        <th>Status</th>
                        <th>Core Ram Allocation</th>
                        <th>Execution Event Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td><span class="badge-signature">{{ $log->command_signature }}</span></td>
                            <td>
                                <span class="badge {{ $log->status === 'Success' ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }} px-2 py-1 fs-8">
                                    {{ $log->status }}
                                </span>
                            </td>
                            <td>{{ $log->memory_used }} MB</td>
                            <td class="text-muted small">{{ $log->executed_at->format('M d, Y h:i:A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4 small">Pipeline pipeline history buffer is empty.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let memoryChart;

    $('.btn-run').on('click', function() {
        const command = $(this).data('command');
        const button = $(this);
        
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

        $.ajax({
            url: '{{ route("task.run") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                command: command
            },
            success: function(response) {
                button.prop('disabled', false).html('<i class="fas fa-play"></i> Trigger Now');
                toastr.success(response.message);
                $('#consoleOutputPanel').removeClass('d-none');
                $('#consoleOutputBuffer').html(`> ${response.output}`);
                renderChartTimeline();
            },
            error: function(xhr) {
                button.prop('disabled', false).html('<i class="fas fa-play"></i> Trigger Now');
                toastr.error('Pipeline process failure encountered.');
            }
        });
    });

    $('.select-interval').on('change', function() {
        const command = $(this).data('command');
        const interval = $(this).val();

        $.ajax({
            url: '{{ route("task.updateInterval") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                command: command,
                interval: interval
            },
            success: function(response) {
                toastr.success(response.message);
            },
            error: function() {
                toastr.error('Failed to configure pipeline sequence.');
            }
        });
    });

    function renderChartTimeline() {
        $.ajax({
            url: '{{ route("task.chartData") }}',
            method: 'GET',
            success: function(response) {
                if (memoryChart) memoryChart.destroy();

                const ctx = document.getElementById('memoryLineChart').getContext('2d');
                memoryChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: response.labels,
                        datasets: [{
                            label: 'Core RAM Allocation (MB)',
                            data: response.data,
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.05)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.1,
                            pointRadius: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { font: { size: 10 } } },
                            x: { ticks: { font: { size: 9 }, maxRotation: 45 } }
                        }
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        renderChartTimeline();
        setInterval(renderChartTimeline, 30000);
    });
</script>
@endsection