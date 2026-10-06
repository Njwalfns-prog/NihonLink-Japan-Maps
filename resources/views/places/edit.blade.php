<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Tempat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('places.update', $place) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Tempat</label>
                        <input type="text" name="name" value="{{ old('name', $place->name) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Kategori</label>
                        <input type="text" name="category" value="{{ old('category', $place->category) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $place->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Koordinat -->
                    <div class="mb-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Latitude</label>
                            <input type="text" name="latitude" id="latitude"
                                   value="{{ old('latitude', $place->latitude) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50" required>
                            @error('latitude') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Longitude</label>
                            <input type="text" name="longitude" id="longitude"
                                   value="{{ old('longitude', $place->longitude) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50" required>
                            @error('longitude') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Peta Leaflet -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Klik peta untuk mengubah lokasi</label>
                        <div id="map" style="height: 300px; border-radius: 8px;"></div>
                    </div>

                    <!-- Gambar -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Gambar (kosongkan jika tidak ingin mengubah)</label>
                        @if($place->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/'.$place->image) }}" class="h-24 rounded" alt="Gambar saat ini">
                                <p class="text-xs text-gray-500 mt-1">Gambar saat ini</p>
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('places.index') }}" class="px-4 py-2 text-gray-600 hover:underline">Batal</a>
                        <button type="submit"
                                class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const initLat = {{ $place->latitude }};
        const initLng = {{ $place->longitude }};

        const map = L.map('map').setView([initLat, initLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker = L.marker([initLat, initLng]).addTo(map);

        map.on('click', function (e) {
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            marker.setLatLng(e.latlng);
        });

        function syncMapFromInputs() {
            const lat = parseFloat(document.getElementById('latitude').value);
            const lng = parseFloat(document.getElementById('longitude').value);
            if (isNaN(lat) || isNaN(lng)) return;
            if (lat < -90 || lat > 90 || lng < -180 || lng > 180) return;
            const ll = L.latLng(lat, lng);
            marker.setLatLng(ll);
            map.setView(ll, map.getZoom());
        }

        document.getElementById('latitude').addEventListener('input', syncMapFromInputs);
        document.getElementById('longitude').addEventListener('input', syncMapFromInputs);
    </script>
</x-app-layout>
