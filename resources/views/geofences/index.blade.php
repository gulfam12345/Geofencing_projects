@extends('layouts.app')

@section('title', 'Geofencing')

@section('content')
<div class="container-fluid py-5">

    <!-- Page Header -->
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">Geofencing</h2>
        <p class="text-muted">Draw, edit, and manage geofences on the map</p>
    </div>

    <!-- Map Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div id="map" style="height:600px; width:100%; border-radius: 8px;"></div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>

<script>
const lat = {{ $user->lat ?? 31.5204 }};
const lng = {{ $user->lng ?? 74.3587 }};

// Initialize map
const map = L.map('map').setView([lat, lng], 13);

// Base layer
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Feature group for drawn items
const drawnItems = new L.FeatureGroup();
map.addLayer(drawnItems);

// Draw control
const drawControl = new L.Control.Draw({
    draw: {
        polygon: true,
        polyline: false,
        rectangle: true,
        circle: true,
        marker: true,
        circlemarker: false
    },
    edit: { featureGroup: drawnItems }
});
map.addControl(drawControl);

// Load existing geofences
fetch('/geofences')
.then(res => res.json())
.then(data => {
    if(data.length === 0){
        // Default circle if no geofence
        const defaultCircle = L.circle([lat, lng], {
            radius: 500,
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.3
        }).addTo(drawnItems);

        // Save to DB
        fetch('/geofences', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ name: 'Default Area', coordinates: defaultCircle.toGeoJSON() })
        })
        .then(r=>r.json())
        .then(resp => defaultCircle._geofenceId = resp.id);
    } else {
        data.forEach(item => {
            const layer = L.geoJSON(item.coordinates).addTo(drawnItems);
            layer.eachLayer(l => l._geofenceId = item.id);
        });
    }
});

// Create new geofence
map.on(L.Draw.Event.CREATED, function(e){
    const layer = e.layer;
    drawnItems.addLayer(layer);
    fetch('/geofences', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ name: 'Area', coordinates: layer.toGeoJSON() })
    })
    .then(r=>r.json())
    .then(resp => layer._geofenceId = resp.id);
});

// Edit geofence
map.on('draw:edited', function(e){
    e.layers.eachLayer(layer=>{
        fetch('/geofences/'+layer._geofenceId, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ coordinates: layer.toGeoJSON() })
        });
    });
});

// Delete geofence
map.on('draw:deleted', function(e){
    e.layers.eachLayer(layer=>{
        fetch('/geofences/'+layer._geofenceId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
    });
});
</script>
@endsection
