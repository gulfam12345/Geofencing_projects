@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    <h4>Dashboard</h4>
    <canvas id="timelineChart" height="100"></canvas>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text fs-2">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Geofences</h5>
                    <p class="card-text fs-2">{{ $totalGeofences }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('timelineChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($dates),
        datasets: [{
            label: 'New Users per Day',
            data: @json($userCounts),
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true },
            tooltip: { mode: 'index', intersect: false }
        },
        scales: {
            x: { title: { display: true, text: 'Date' } },
            y: { title: { display: true, text: 'Users' }, beginAtZero: true }
        }
    }
});
</script>
@endsection
