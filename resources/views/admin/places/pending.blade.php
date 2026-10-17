<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pending Places – Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="max-w-5xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Pengajuan Lokasi – Pending</h1>
        <@foreach($places as $place)
            <div class="bg-white shadow rounded p-4 mb-4"
                 x-data="{status: '{{ $place->status }}'}">
                <h3 class="text-lg font-semibold">{{ $place->name }}</h3>
                <p class="text-sm text-gray-600">Kategori: {{ $place->category }}</p>
                <p class="text-sm text-gray-600">Deskripsi: {{ $place->description }}</p>
                <p class="text-sm text-gray-600">Koordinat: {{ $place->latitude }}, {{ $place->longitude }}</p>
                @if($place->image)
                    <img src="{{ asset('storage/'.$place->image) }}" class="h-32 mt-2 rounded" alt="Foto tempat">
                @endif
                <form method="POST" action="{{ route('places.approve', $place) }}" class="inline">
                    @csrf
                    <button type="submit" class="mr-2 px-3 py-1 bg-green-600 text-white rounded">Setuju</button>
                </form>
                <form method="POST" action="{{ route('places.delete', $place) }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Tolak</button>
                </form>
            </div>
        <@endforeach>
    </div>
</body>
</html>