<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J-Map - Japanese Subculture Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .leaflet-container { touch-action: auto !important; outline: none; }
        .leaflet-popup-content { margin: 12px !important; }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.css" />
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navbar -->
    <header class="bg-white shadow-2xl">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Brand -->
            <a href="{{ url('/') }}" class="text-2xl font-bold text-gray-900">J-Map</a>
            <!-- Right -->
            @auth
                <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-blue-600 transition font-semibold">Dashboard</a>
            @else
                <a href="{{ url('/login') }}" class="text-gray-700 hover:text-blue-600 transition font-semibold">Login</a>
            @endauth
        </nav>
    </header>

    <!-- Container -->
    <div class="max-w-7xl mx-auto p-6">
        <!-- Peta -->
        <div id="map" class="relative w-full h-[500px] rounded-2xl shadow-2xl" style="z-index: 1;"></div>

        <!-- Filter -->
        <div class="flex flex-wrap gap-3">
    <button class="px-4 py-2 bg-white text-gray-700 rounded-lg shadow-sm hover:shadow transition font-medium category-filter cursor-pointer" onclick="filterPlaces('semua')">Semua</button>
    @foreach($categories as $cat)
        <button class="px-4 py-2 bg-white text-gray-700 rounded-lg shadow-sm hover:shadow transition font-medium category-filter cursor-pointer" onclick="filterPlaces('{{ strtolower($cat) }}')">{{ ucfirst($cat) }}</button>
    @endforeach
</div>

        <!-- Daftar Lokasi (Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4">
            @foreach($places as $place)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden place-card" data-category="{{ strtolower($place->category) }}">
                    @if($place->image_url)
                        <img src="{{ $place->image_url }}" alt="{{ $place->name }}" class="object-cover h-36 w-full rounded-t-xl" onerror="this.onerror=null; this.src='https://placehold.co/600x400?text=No+Image';">
                    @else
                        <div class="h-36 bg-gray-200 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159a2.25 2.25 0 0 0 3.182 0l5.159-5.159"/></svg>
                        </div>
                    @endif
                    <div class="p-3">
                        <span class="inline-block bg-indigo-100 text-indigo-800 px-2.5 py-0.5 rounded-full text-sm mb-2">{{ $place->category }}</span>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $place->name }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ \Illuminate\Support\Str::limit($place->description, 80) }}</p>
                        <div class="flex justify-between items-center">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-1 px-2 rounded" onclick='openDetailModal(@json($place))'>Detail</button>
                            <button class="bg-green-600 hover:bg-green-700 text-white text-xs font-medium py-1 px-2 rounded" onclick="window.open('https://www.google.com/maps/dir/?api=1&destination={{ $place->latitude }},{{ $place->longitude }}','_blank')">Rute</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal Detail -->
        <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg overflow-hidden shadow-lg max-w-lg w-full relative">
                <button class="absolute top-2 right-2 text-gray-500 hover:text-gray-700" onclick="closeDetailModal()">&times;</button>
                <img id="modalImg" src="" alt="" class="w-full h-64 object-cover" onerror="this.onerror=null; this.src='https://placehold.co/600x400?text=No+Image'">
                <div class="p-4">
                    <h2 id="modalTitle" class="text-2xl font-bold mb-2"></h2>
                    <span id="modalBadge" class="inline-block bg-indigo-100 text-indigo-800 px-2.5 py-0.5 rounded-full text-sm mb-2"></span>
                    <p id="modalDesc" class="text-gray-700 mb-4"></p>
                    <p class="text-sm text-gray-500 mb-2" id="modalCoords"></p>
                    <a id="modalMapLink" href="#" target="_blank" class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded">Buka di Google Maps</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.js"></script>
    <script>
        var map = L.map('map', { dragging: true, touchZoom: true, scrollWheelZoom: true, gestureHandling: true }).setView([-2.5489, 118.0149], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        function openDetailModal(data) {
            document.getElementById('modalImg').src = data.image_url ? data.image_url : 'https://placehold.co/600x400?text=No+Image';
            document.getElementById('modalTitle').textContent = data.name;
            document.getElementById('modalBadge').textContent = data.category;
            document.getElementById('modalDesc').textContent = data.description || '';
            document.getElementById('modalCoords').textContent = `Lat: ${data.latitude}, Lng: ${data.longitude}`;
            document.getElementById('modalMapLink').href = `https://www.google.com/maps/search/?api=1&query=${data.latitude},${data.longitude}`;
            document.getElementById('detailModal').classList.remove('hidden');
        }
        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        var places = @json($places);
        var allMarkers = [];

        places.forEach(function(place) {
            let fallbackImg = 'https://images.unsplash.com/photo-1580442531649-6f947385f0cd?q=80&w=400&auto=format&fit=crop';
            let imageUrl = place.image ? '/storage/' + place.image : fallbackImg;

            let markerColor = '#3b82f6';
            let markerIcon = '<i class="fa-solid fa-location-dot"></i>';
            let category = place.category.toLowerCase();

            if (category.includes('cosplay')) {
                markerColor = '#8b5cf6';
                markerIcon = '<i class="fa-solid fa-mask"></i>';
            } else if (category.includes('event') || category.includes('jejepangan')) {
                markerColor = '#f59e0b';
                markerIcon = '<i class="fa-solid fa-ticket"></i>';
            } else if (category.includes('toko') || category.includes('merch')) {
                markerColor = '#10b981';
                markerIcon = '<i class="fa-solid fa-store"></i>';
            } else if (category.includes('makanan') || category.includes('resto') || category.includes('cafe')) {
                markerColor = '#ef4444';
                markerIcon = '<i class="fa-solid fa-bowl-food"></i>';
            }

            let customMarker = L.divIcon({
                className: 'bg-transparent',
                html: `
                    <div style="position: relative; width: 36px; height: 44px; filter: drop-shadow(0px 4px 4px rgba(0,0,0,0.3));">
                        <div style="position: absolute; width: 34px; height: 34px; background-color: ${markerColor}; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 2px solid white; left: 1px; top: 1px;"></div>
                        <div style="position: absolute; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; left: 0; top: 0; font-size: 15px; color: white; z-index: 10;">${markerIcon}</div>
                    </div>
                `,
                iconSize: [36, 44],
                iconAnchor: [18, 44],
                popupAnchor: [0, -44]
            });

            let popupContent = `
                <div style="font-family: ui-sans-serif, system-ui, sans-serif;">
                    <img src="${imageUrl}" onerror="this.onerror=null; this.src='${fallbackImg}';" style="width: 100%; height: 90px; object-fit: cover; border-radius: 8px; margin-bottom: 8px;">
                    <h3 style="margin: 0 0 4px 0; font-size: 14px; font-weight: bold; color: #1f2937; line-height: 1.2;">${place.name}</h3>
                    <p style="margin: 0 0 6px 0; font-size: 11px; color: ${markerColor}; font-weight: bold;">${markerIcon} ${place.category}</p>
                    <a href="#" style="display: block; text-align: center; background-color: #3b82f6; color: white; padding: 6px 0; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold;">Lihat Detail &rarr;</a>
                </div>
            `;

            let marker = L.marker([place.latitude, place.longitude], { icon: customMarker })
                .bindPopup(popupContent, { maxWidth: 180, minWidth: 180 });

            let markerCategory = place.category.toLowerCase();
            allMarkers.push({ layer: marker, category: markerCategory });
            marker.addTo(map);
        });

        window.filterPlaces = function(selectedCategory) {
            selectedCategory = selectedCategory.toLowerCase();

            allMarkers.forEach(function(item) {
                if (selectedCategory === 'semua' || item.category.includes(selectedCategory)) {
                    if (!map.hasLayer(item.layer)) map.addLayer(item.layer);
                } else {
                    if (map.hasLayer(item.layer)) map.removeLayer(item.layer);
                }
            });

            let cards = document.querySelectorAll('.place-card');
            cards.forEach(function(card) {
                let cardCat = card.getAttribute('data-category') || '';
                if (selectedCategory === 'semua' || cardCat.includes(selectedCategory)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        };
    </script>
</body>
</html>