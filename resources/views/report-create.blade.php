@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 250px !important;
        width: 100%;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var map = L.map('map').setView([-6.200000, 106.816666], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var marker;
        setTimeout(function() {
            map.invalidateSize();
        }, 300);

        // Fungsi Reverse Geocoding untuk mengubah Lat/Lng menjadi Nama Alamat
        function updateAddressFromCoords(lat, lng) {
            var locationInput = document.getElementById('location');
            if (locationInput) {
                // Tampilkan indikator memuat alamat
                locationInput.placeholder = "Mencari alamat lokasi...";
            }

            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name && locationInput) {
                        locationInput.value = data.display_name;
                    }
                })
                .catch(error => {
                    console.error('Error Reverse Geocoding:', error);
                });
        }

        // 1. Deteksi Lokasi Awal Pengguna
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                map.setView([lat, lng], 15);

                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }

                // Isi otomatis alamat berdasarkan GPS jika kolom alamat masih kosong
                if (!document.getElementById('location').value) {
                    updateAddressFromCoords(lat, lng);
                }
            });
        }

        // 2. Klik pada Peta
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }

            // Update otomatis input lokasi saat peta diklik
            updateAddressFromCoords(lat, lng);
        });
    });

    function previewImage(input) {
        if (input.files && input.files[0]) {
            document.getElementById('upload-text').innerText = "Foto terpilih: " + input.files[0].name;
        }
    }
</script>
@endpush

<x-layouts.app title="Form Laporan">
    <div class="border-b border-line bg-surface-muted">
        <x-container class="py-4">
            <h1 class="font-semibold text-ink">Form Laporan</h1>
        </x-container>
    </div>

    <x-container class="py-8">
        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-500/20 bg-red-500/10 p-4 text-sm text-red-600" role="alert">
                <p class="font-medium">Laporan belum dapat disimpan:</p>
                <ul class="mt-2 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-lg bg-red-500/10 p-4 border border-red-500/20 text-red-500 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">

            {{-- Upload gambar --}}
            <div>
                <label
                    for="image"
                    class="grid min-h-56 cursor-pointer place-items-center rounded-card border-2 border-dashed border-line bg-surface text-center transition hover:border-brand-400 hover:bg-brand-50"
                >
                    <div class="space-y-2 p-6" id="image-preview-container">
                        <svg class="mx-auto size-8 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 16V4m0 0L8 8m4-4l4 4" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-sm font-medium text-ink" id="upload-text">Upload Image Box</p>
                        <p class="text-xs text-ink-muted">Klik untuk memilih foto kerusakan (JPG atau PNG)</p>
                    </div>
                </label>
                <input id="image" name="image" type="file" accept="image/*" class="hidden" required onchange="previewImage(this)">
            </div>

            <x-input value="{{ old('title') }}" name="title" label="Judul Laporan" placeholder="Contoh: Jalan rusak di Jl Letjen Soeprapto" />

            <div class="grid gap-6 sm:grid-cols-2">
                <x-input value="{{ old('reporter') }}" name="reporter" label="Nama Pelapor" placeholder="Nama lengkap" />
                <x-input value="{{ old('phone') }}" name="phone" type="tel" label="No Telepon/WhatsApp" placeholder="08xxxxxxxxxx" />
            </div>

            <x-textarea name="description" label="Deskripsi" rows="5" placeholder="Jelaskan kondisi kerusakan sedetail mungkin">{{ old('description') }}</x-textarea>

            {{-- Lokasi --}}
            <div class="space-y-1.5">
                <span class="block text-sm font-medium text-ink">Lokasi</span>
                <div class="grid gap-6 sm:grid-cols-2">
                    {{-- Container Peta --}}
                    <div id="map" class="z-0 h-64 w-full overflow-hidden rounded-card border border-line" style="min-height: 250px;"></div>
                    <div>
                        {{-- Diberikan id="location" agar bisa diakses oleh JavaScript --}}
                        <x-input id="location" value="{{ old('location') }}" name="location" placeholder="Ketik alamat lokasi atau klik pada peta" />
                    </div>
                </div>
            </div>

            {{-- Persetujuan --}}
            <div class="space-y-3">
                <x-checkbox name="show_name">Tampilkan nama pada laporan</x-checkbox>
                <x-checkbox name="agreement">
                    Dengan ini saya menyatakan laporan yang saya buat benar sebenar benarnya, dan siap menerima
                    hukuman sesuai dengan undang undang yang berlaku apabila laporan bersifat palsu.
                </x-checkbox>
            </div>

            <x-button type="submit" class="rounded-full">Kirim Laporan</x-button>
        </form>
    </x-container>
</x-layouts.app>