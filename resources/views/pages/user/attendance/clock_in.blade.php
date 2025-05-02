@extends('pages.user.layouts.master')

@section('content')
<div class="mt-4">
    <h2>Clock In</h2>
    <hr>

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div id="userMap" style="height: 470px"></div>
            </div>
        </div>
    </div>
</div>
<!-- Leaflet -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"
    integrity="sha512-ozq8xQKq6urvuU6jNgkfqAmT7jKN2XumbrX1JiB3TnF7tI48DPI4Gy1GXKD/V3EExgAs1V+pRO7vwtS1LHg0Gw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function () {
        // Initialize the map
        var map = L.map('userMap').setView([-6.200000, 106.816666], 13);

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Add a marker for the user's location
        var userMarker = L.marker([-6.200000, 106.816666]).addTo(map)
            .bindPopup('Your Location')
            .openPopup();

        // Add a circle around the user's location
        var userCircle = L.circle([-6.200000, 106.816666], {
            color: 'blue',
            fillColor: '#30f',
            fillOpacity: 0.5,
            radius: 100
        }).addTo(map);
    })
</script>
@endsection
