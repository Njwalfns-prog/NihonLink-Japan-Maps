<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Tempat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
    <table class="min-w-full bg-white shadow rounded">
        <thead>
            <tr>
                <th class="px-4 py-2 border">Nama</th>
                <th class="px-4 py-2 border">Kategori</th>
                <th class="px-4 py-2 border">Foto</th>
                <th class="px-4 py-2 border">Koordinat</th>
                <th class="px-4 py-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($places as $place)
                <tr>
                    <td class="px-4 py-2 border">{{ $place->name }}</td>
                    <td class="px-4 py-2 border">{{ $place->category }}</td>
                    <td class="px-4 py-2 border">
                        @if($place->image)
                            <img src="{{ asset('storage/'.$place->image) }}" alt="{{ $place->name }}" class="h-16 w-16 object-cover rounded" />
                        @else
                            <span class="text-gray-500">Tidak ada</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 border">
                        {{ $place->latitude }}, {{ $place->longitude }}
                    </td>
                    <td class="px-4 py-2 border">
                        <a href="{{ route('places.edit', $place) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                        <form action="{{ route('places.destroy', $place) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus tempat ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-2 text-center">Tidak ada data tempat.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        <a href="{{ route('places.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Tambah Tempat Baru</a>
    </div>
            </div>
        </div>
    </div>
</x-app-layout>