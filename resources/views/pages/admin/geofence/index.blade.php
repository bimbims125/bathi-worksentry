@extends('pages.admin.layouts.master')
@push('vendor-style')
<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<!--datatable responsive css-->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endpush

@push('vendor-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!--datatable js-->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
@endpush

@push('page-script')
<!-- Leaflet -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"
    integrity="sha512-ozq8xQKq6urvuU6jNgkfqAmT7jKN2XumbrX1JiB3TnF7tI48DPI4Gy1GXKD/V3EExgAs1V+pRO7vwtS1LHg0Gw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- End leaflet -->
<script>
    $(document).ready(function () {
        // $('#geofenceTable').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     ajax: {
        //         url: "{{ route('admin.geofence.index') }}",
        //         type: 'GET',
        //     },
        //     columns: [
        //         {data: 'id', name: 'id'},
        //         {data: 'name', name: 'name'},
        //         {data: 'description', name: 'description'},
        //         {data: 'created_at', name: 'created_at'},
        //         {data: 'updated_at', name: 'updated_at'},
        //         {data: 'action', name: 'action', orderable: false, searchable: false},
        //     ],
        //     responsive: true,
        // });
        let map = L.map('map').setView([-6.464074197735817, 106.84978143128386], 18);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">'
        }).addTo(map);

        // Draw Geofences
        const geofences = @json($geofences);
        console.log(geofences);
        geofences.forEach((geofence) => {
            const coordinates = JSON.parse(geofence.coordinates);
            const color = geofence.status === 'active' ? 'green' : 'red';
            const polygon = L.polygon(coordinates, {
                color
            }).addTo(map);
            polygon.bindTooltip(geofence.name, {
                permanent: true,
                direction: 'center'
            });
        });

    });

</script>
@endpush
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Admin</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Geofence</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Geofence List</h4>
            </div>
            <div class="card-body">
                <div id="map" style="height: 500px;"></div>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
@endsection
