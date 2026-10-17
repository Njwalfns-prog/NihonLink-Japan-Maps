<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajukan Tempat Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="mb-4 text-sm text-gray-600 bg-blue-50 border border-blue-200 rounded p-3">
                    Lokasi yang Anda ajukan akan diverifikasi terlebih dahulu oleh Admin sebelum ditampilkan di peta publik.
                </p>

                <form method="POST" action="{{ route('places.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Tempat</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Kategori</label>
                        <input type="text" name="category" value="{{ old('category') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Koordinat (readonly, diisi via peta) -->
                    <div class="mb-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Latitude</label>
                            <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50" required>
                            @error('latitude') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Longitude</label>
                            <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50" required>
                            @error('longitude') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Peta Leaflet -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Klik peta untuk memilih lokasi</label>
                        <div id="map" style="height: 300px; border-radius: 8px;"></div>
                    </div>

                    <!-- Gambar -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Gambar (opsional)</label>
                        <input type="file" name="image" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @endif
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm">
                            Simpan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const map = L.map('map').setView([-2.5, 118.0], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker = null;

        map.on('click', function (e) {
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);

            document.getElementById('latitude').value  = lat;
            document.getElementById('longitude').value = lng;

            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }
        });

        function syncMapFromInputs() {
            const lat = parseFloat(document.getElementById('latitude').value);
            const lng = parseFloat(document.getElementById('longitude').value);
            if (isNaN(lat) || isNaN(lng)) return;
            if (lat < -90 || lat > 90 || lng < -180 || lng > 180) return;
            const ll = L.latLng(lat, lng);
            if (marker) {
                marker.setLatLng(ll);
            } else {
                marker = L.marker(ll).addTo(map);
            }
            map.setView(ll, map.getZoom());
        }

        document.getElementById('latitude').addEventListener('input', syncMapFromInputs);
        document.getElementById('longitude').addEventListener('input', syncMapFromInputs);
    </script>
</x-app-layout>
