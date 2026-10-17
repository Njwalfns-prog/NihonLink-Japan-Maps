<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Places Pending</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="max-w-5xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Pengajuan Lokasi – Pending</h1>
        <div class="bg-white shadow rounded p-4 mb-4"
             x-data="{status: 'pending'}">
            <h3 class="text-lg font-semibold">Name</h3>
            <p class="text-sm text-gray-600">Kategori: Kategori</p>
            <p class="text-sm text-gray-600">Deskripsi: Deskripsi</p>
            <p class="text-sm text-gray-600">Koordinat: Latitude, Longitude</p>
            @if(true)
                <img src="https://via.placeholder.com/200x150" class="h-32 mt-2 rounded" alt="Foto tempat">
            @endif
            <form method="POST" action="#" class="inline">
                @csrf
                <button type="submit" class="mr-2 px-3 py-1 bg-green-600 text-white rounded">Setuju</button>
            </form>
            <form method="POST" action="#" class="inline">
                @csrf
                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Tolak</button>
            </form>
        </div>
    </div>
</body>
</html>
