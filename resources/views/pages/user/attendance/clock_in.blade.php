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
            <div class="card-footer">
                <div class="col-md-6">
                    <button type="button" class="btn btn-success" id="clockInButton" data-bs-toggle="modal"
                        data-bs-target="#clockInModal" >Clock In</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="clockInModal" tabindex="-1" aria-labelledby="clockInModal" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clockInModal">Attendance In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.clock-in.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-xxl-12">
                                <div class="d-flex justify-content-center">
                                    <div id="webCam" style="position: relative; width: 640px; height: 380px;"></div>
                                </div>
                                <div class="d-flex justify-content-center mt-2">
                                    <span id="recognitionText"></span>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="button" id="captureButton" class="btn btn-success mt-2"
                                        disabled>Capture</button>
                                </div>
                            </div>
                            <div class="col-xxl-6">
                                <input type="hidden" name="latlong" id="latlong" readonly class="form-control">
                            </div>
                            <div class="col-xxl-6">
                                <input type="hidden" name="location" id="location" readonly class="form-control">
                            </div>
                            <div class="col-xxl-6">
                                <textarea id="clockInPicture" name="clock_in_picture" readonly class="form-control"
                                    style="display: none"></textarea>
                            </div>
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button id="closeModal" type="button" class="btn btn-light"
                                        data-bs-dismiss="modal">Close</button>
                                    <button id="submitCheckinButton" type="submit" class="btn btn-primary"
                                        disabled>Submit</button>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
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

<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
    $(document).ready(function () {
        // Initialize the map
        // let map = L.map('userMap').setView([-6.200000, 106.816666], 13);

        let map; // Definisikan map di luar callback geolocation
        let userMarker; // Definisikan marker global agar bisa diperbarui
        let eligibleArea = false; // Cek status area
        let geofenceName = ''; // Nama geofence yang eligible
        let userLatLong = '';

        function initializeMap(position) {
            const {
                latitude,
                longitude
            } = position.coords;

            // Inisialisasi peta pada lokasi pengguna
            map = L.map('userMap').setView([latitude, longitude], 18);

            // Tambahkan tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Tambahkan marker pertama kali
            userMarker = L.marker([latitude, longitude]).addTo(map)
                .bindPopup("You are here!")
                .openPopup();

            // Aktifkan pelacakan lokasi secara real-time
            trackLocation();

            // Draw Polygon Geofences
            const geofencesPolygon = @json($geofences_polygon);
            geofencesPolygon.forEach((geofence) => {
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
        }

        // Fungsi untuk melacak lokasi pengguna secara real-time
        function trackLocation() {
            navigator.geolocation.watchPosition(
                (position) => {
                    const {
                        latitude,
                        longitude
                    } = position.coords;

                    if (userMarker) {
                        // Perbarui posisi marker tanpa membuat marker baru
                        userMarker.setLatLng([latitude, longitude]);
                    }

                    // Fokuskan peta ke lokasi terbaru
                    map.setView([latitude, longitude], 18);

                    // ====================================================
                    // Cek apakah user berada dalam area geofence
                    eligibleArea = false; // Reset status eligible area
                    geofenceName = ''; // Reset nama geofence

                    // Polygon geofences
                    if (!eligibleArea) {
                        const geofencesPolygon = @json($geofences_polygon);
                        geofencesPolygon.some((geofence) => {
                            const coordinates = JSON.parse(geofence.coordinates);
                            const polygon = L.polygon(coordinates);
                            const userLatLng = L.latLng(latitude, longitude);

                            function isMarkerInsidePolygon(marker, poly) {
                                const polyPoints = poly.getLatLngs()[0];
                                const x = marker.lat,
                                    y = marker.lng;
                                let inside = false;

                                for (let i = 0, j = polyPoints.length - 1; i < polyPoints
                                    .length; j = i++) {
                                    const xi = polyPoints[i].lat,
                                        yi = polyPoints[i].lng;
                                    const xj = polyPoints[j].lat,
                                        yj = polyPoints[j].lng;

                                    const intersect = ((yi > y) !== (yj > y)) &&
                                        (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
                                    if (intersect) inside = !inside;
                                }
                                return inside;
                            }

                            if (isMarkerInsidePolygon(userLatLng, polygon)) {
                                eligibleArea = true;
                                geofenceName = geofence.name;
                                userLatLong = userLatLng
                                return true;
                            }
                            return false;
                        });
                    }

                    // console.log(geofenceName)
                    // Change status of button
                    if (eligibleArea) {
                        // $('#clockInButton').prop('disabled', false); // Enable button
                        $('#location').val(geofenceName); // Set value of location input
                        $('#latlong').val(userLatLong); // Set value of latlong input
                    } else {
                        $('#latlong').val("test latlong"); // Set value of latlong input
                        // $('#clockInButton').prop('disabled', true); // Disable button
                    }
                    // ====================================================
                },
                (error) => {
                    console.error(`Error Code: ${error.code}, Message: ${error.message}`);
                }, {
                    enableHighAccuracy: true,
                    maximumAge: 0
                }
            );
        }

        // Tangani error jika geolokasi gagal
        function handleError(error) {
            console.error(`Error Code: ${error.code}, Message: ${error.message}`);
            alert('Unable to retrieve your location.');
        }

        // Pastikan geolocation tersedia di browser
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(initializeMap, handleError, {
                enableHighAccuracy: true
            });
        } else {
            alert('Geolocation is not supported by your browser.');
        }

    });
    let videoStream = null;
    let videoElement = null;
    let faceDetected = false; // Track if a face is detected
    let canvasElement = null; // Canvas to draw landmarks

    // Load FaceAPI models
    async function loadModels() {
        try {
            // Load required models
            await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('/models'); // Load landmark model
            console.log("FaceAPI models loaded successfully.");
        } catch (error) {
            console.error("Error loading FaceAPI models:", error);
        }
    }

    // Start webcam
    async function startWebcam() {
        if (!videoElement) {
            videoElement = document.createElement('video');
            videoElement.setAttribute('autoplay', true);
            videoElement.setAttribute('muted', true);
            videoElement.style.width = "100%";
            videoElement.style.height = "100%";
            document.getElementById('webCam').appendChild(videoElement);
        }

        // Create a canvas element for drawing landmarks
        canvasElement = document.createElement('canvas');
        canvasElement.style.position = 'absolute';
        canvasElement.style.top = '0';
        canvasElement.style.left = '0';
        canvasElement.style.width = "100%";
        canvasElement.style.height = "100%";
        document.getElementById('webCam').appendChild(canvasElement);

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: true
            });
            videoElement.srcObject = stream;
            videoStream = stream;

            videoElement.onloadedmetadata = function () {
                videoElement.play();

                // Set ukuran fisik canvas sesuai video
                canvasElement.width = videoElement.videoWidth;
                canvasElement.height = videoElement.videoHeight;

                detectFaces(); // Start face detection
            };
        } catch (error) {
            console.error('Error accessing webcam: ', error);
        }
    }

    // Detect faces and draw landmarks
    async function detectFaces() {
        const options = new faceapi.TinyFaceDetectorOptions();
        const captureButton = document.getElementById('captureButton');

        const displaySize = {
            width: videoElement.videoWidth,
            height: videoElement.videoHeight
        };
        faceapi.matchDimensions(canvasElement, displaySize);

        setInterval(async () => {
            if (!faceapi) {
                console.error("FaceAPI is not loaded.");
                return;
            }

            // Detect faces
            const detections = await faceapi.detectAllFaces(videoElement, options)
                .withFaceLandmarks(); // Include landmarks if needed

            // Clear the canvas
            const context = canvasElement.getContext('2d');
            context.clearRect(0, 0, canvasElement.width, canvasElement.height);

            // Resize detections
            const resizedDetections = faceapi.resizeResults(detections, displaySize);

            // Draw bounding boxes
            faceapi.draw.drawDetections(canvasElement, resizedDetections);

            // Enable capture button if a single face is detected with high confidence
            if (detections.length === 1) {
                faceDetected = true;
                captureButton.disabled = false;
            } else {
                faceDetected = false;
                captureButton.disabled = true;
            }

        }, 100); // Run every 100ms
    }



    // Stop webcam
    function stopWebcam() {
        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
            console.log('Webcam stopped');
        }

        if (videoElement) {
            videoElement.srcObject = null;
            videoElement.remove();
            videoElement = null;
        }

        if (canvasElement) {
            canvasElement.remove();
            canvasElement = null;
        }
    }

    // Capture image from webcam
    async function captureImage() {
        if (videoElement && faceDetected) {
            const canvas = document.createElement('canvas');
            canvas.width = videoElement.videoWidth;
            canvas.height = videoElement.videoHeight;

            const context = canvas.getContext('2d');
            context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);

            // Get the raw image data
            const imageDataUrl = canvas.toDataURL('image/png');

            // Replace video with captured image
            const imgElement = document.createElement('img');
            imgElement.src = imageDataUrl;
            imgElement.style.width = "100%";
            imgElement.style.height = "100%";
            document.getElementById('webCam').innerHTML = '';
            document.getElementById('webCam').appendChild(imgElement);

            // Store raw image data in input field
            document.getElementById('clockInPicture').value = imageDataUrl;
            document.getElementById('submitCheckinButton').disabled = false;

            // Change button to "Retry"
            const captureButton = document.getElementById('captureButton');
            captureButton.textContent = 'Retry';
            captureButton.classList.remove('btn-success');
            captureButton.classList.add('btn-warning');
            captureButton.removeEventListener('click', captureImage);
            captureButton.addEventListener('click', retryCapture);
        } else {
            console.error('No face detected, cannot capture image');
        }
    }

    // Retry capture
    function retryCapture() {
        // Clear value if user retries taking a picture
        document.getElementById('clockInPicture').value = '';
        // Disable button if no picture is taken
        if (!document.getElementById('clockInPicture').value) {
            document.getElementById('submitCheckinButton').disabled = true;
        }

        // Clear the webcam div and restart the webcam
        document.getElementById('webCam').innerHTML = '';
        videoElement = null; // Reset video element reference
        canvasElement = null; // Reset canvas element reference
        startWebcam();

        // Change the retry button back to capture button
        const captureButton = document.getElementById('captureButton');
        captureButton.textContent = 'Capture';
        captureButton.classList.remove('btn-warning');
        captureButton.classList.add('btn-success');
        captureButton.removeEventListener('click', retryCapture);
        captureButton.addEventListener('click', captureImage);
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', async () => {
        await loadModels(); // Load models when the page loads
    });

    document.getElementById('captureButton').addEventListener('click', captureImage);
    document.getElementById('clockInModal').addEventListener('shown.bs.modal', startWebcam);
    document.getElementById('clockInModal').addEventListener('hidden.bs.modal', stopWebcam);
    document.getElementById('closeModal').addEventListener('click', stopWebcam);

</script>
@endsection
