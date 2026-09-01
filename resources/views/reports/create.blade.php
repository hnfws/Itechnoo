<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Pengaduan Infrastruktur</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- 1. Include Leaflet.js (Peta Interaktif) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-slate-100 min-h-screen py-8">

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <h1 class="text-xl font-bold text-slate-900 mb-4">📢 Laporkan Kerusakan Infrastruktur</h1>

        <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Input Judul & Deskripsi -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Laporan</label>
                <input type="text" name="title" required placeholder="Jalan berlubang / Lampu mati" class="w-full border rounded-lg p-2 text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Foto Kerusakan</label>
                <input type="file" name="image" accept="image/*" required class="w-full text-xs">
            </div>

            <!-- 2. UI Komponen Peta Interaktif -->
            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-xs font-semibold text-slate-700">Pilih Lokasi di Peta</label>
                    <button type="button" onclick="getCurrentLocation()" class="text-xs text-sky-600 font-medium hover:underline">
                        🎯 Gunakan GPS Saya
                    </button>
                </div>

                <!-- Element Wadah Peta -->
                <div id="map" class="w-full h-64 rounded-lg border border-slate-300"></div>
                <p class="text-[11px] text-slate-400 mt-1">*Klik pada peta untuk menyesuaikan titik lokasi kerusakan.</p>
            </div>

            <!-- Nama Alamat Hasil Geocoding -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Alamat / Patokan Lokasi</label>
                <input type="text" id="location_name" name="location_name" required readonly placeholder="Mengambil alamat dari peta..." class="w-full bg-slate-50 border rounded-lg p-2 text-sm text-slate-600">
            </div>

            <!-- Hidden Input Koordinat (Kirim ke Laravel) -->
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Kerusakan</label>
                <textarea name="description" rows="3" required placeholder="Jelaskan detail kerusakan..." class="w-full border rounded-lg p-2 text-sm"></textarea>
            </div>

            <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 text-white font-bold py-2.5 rounded-lg text-sm transition">
                🚀 Kirim Laporan
            </button>
        </form>
    </div>

    <!-- 3. JavaScript Logic Peta -->
    <script>
        // Set titik default (Misal: Jakarta / Depok)
        let defaultLat = -6.4025;
        let defaultLng = 106.7942;

        // Inisialisasi Leaflet Map
        const map = L.map('map').setView([defaultLat, defaultLng], 13);

        // Tile Layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        // Fungsi Update Alamat berdasarkan Lat/Lng (Reverse Geocoding via Nominatim API)
        async function updateLocationInfo(lat, lng) {
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            document.getElementById('location_name').value = "Mencari nama lokasi...";

            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                const data = await response.json();
                if (data && data.display_name) {
                    document.getElementById('location_name').value = data.display_name;
                } else {
                    document.getElementById('location_name').value = `${lat}, ${lng}`;
                }
            } catch (error) {
                document.getElementById('location_name').value = `${lat}, ${lng}`;
            }
        }

        // Event saat Peta Diklik
        map.on('click', function(e) {
            const { lat, lng } = e.latlng;
            marker.setLatLng([lat, lng]);
            updateLocationInfo(lat, lng);
        });

        // Event saat Marker Digeser (Drag)
        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            updateLocationInfo(position.lat, position.lng);
        });

        // Fitur Gunakan GPS Browser
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.setView([lat, lng], 16);
                    marker.setLatLng([lat, lng]);
                    updateLocationInfo(lat, lng);
                });
            } else {
                alert("Geolokasi tidak didukung oleh browser Anda.");
            }
        }

        // Set koordinat awal saat halaman dimuat
        updateLocationInfo(defaultLat, defaultLng);
    </script>
</body>
</html>