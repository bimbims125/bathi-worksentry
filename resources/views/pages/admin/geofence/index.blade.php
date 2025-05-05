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
         // Get all form
        const coordinatesForm = $('#coordinates');
        const typeForm = $('#type');
        const centerForm = $('#center');
        const radiusForm = $('#radius');

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
        const drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        const drawControl = new L.Control.Draw({
            draw: {
                polygon: {
                    allowIntersection: false,
                    showArea: true,
                    shapeOptions: {
                        color: 'green',
                        fillColor: 'green',
                        fillOpacity: 0.1,
                    }
                },
                circle: {
                    showArea: true,
                    shapeOptions: {
                        color: 'green',
                        fillColor: 'green',
                        fillOpacity: 0.1,
                    }
                },
                polyline: false,
                marker: false,
                rectangle: false,
                circlemarker: false
            }
        });
        map.addControl(drawControl);

        // Add tile layer
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">'
        }).addTo(map);

         // Event when shape is drawn
        map.on('draw:created', (e) => {
                const layer = e.layer;
                if (layer instanceof L.Circle) {
                    const center = layer.getLatLng();
                    const radius = layer.getRadius();
                    typeForm.val('circle');
                    radiusForm.val(radius);
                    centerForm.val(JSON.stringify([center.lat, center.lng]));
                    coordinatesForm.val(0);
                } else {
                    const coordinatesArray = [];
                    const latlngs = layer.getLatLngs();
                    latlngs.flat().forEach((point) => coordinatesArray.push([point.lat, point
                        .lng
                    ]));
                    coordinatesForm.val(JSON.stringify(coordinatesArray));
                    typeForm.val('polygon');
                }
                $('#geofencesModal').modal('show');
                drawnItems.addLayer(layer);
            });

            $('#geofencesModal').on('hidden.bs.modal', () => {
                drawnItems.clearLayers();
            });

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
                <h4 class="card-title">Geofences</h4>
            </div>
            <div class="card-body">
                <div id="map" style="height: 500px;"></div>
            </div>
        </div>
    </div>
</div>
<!-- Geofence Modal -->
<div class="modal fade" id="geofencesModal" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalgridLabel">Create Geofences</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.geofence.store')}}" method="POST" autocomplete="off">
                    @csrf
                    <div class="row g-3">
                        <div class="col-xxl-12">
                            <div class="mb-3">
                                <label for="location_name" class="form-label">Location Name</label>
                                <input type="text" class="form-control" id="locationName" name="name"
                                    placeholder="Enter Location name" required>
                            </div>
                            <div class="mb-3">
                                <input hidden name="type" type="text" class="form-control" id="type" readonly>
                            </div>
                            <div class="mb-3">
                                <input hidden name="radius" type="text" class="form-control" id="radius" readonly>
                            </div>
                            <div class="mb-3">
                                <textarea hidden name="coordinates" class="form-control" id="coordinates"
                                    readonly></textarea>
                            </div>
                            <div class="mb-3">
                                <input hidden name="center" type="text" class="form-control" id="center" readonly>
                            </div>
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
@endsection
