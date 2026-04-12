@extends('layouts.admin')

@section('title', 'Kelola Paket Harga')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Kelola Menu Bundling</h1>
    <a href="{{ route('admin.packages.create') }}" class="text-white bg-indigo-600 hover:bg-indigo-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        Tambah Paket Baru
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Paket</th>
                    <th scope="col" class="px-6 py-3">Harga</th>
                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($packages as $package)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $package->name }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-4">
                            <a href="{{ route('admin.packages.edit', $package->id) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Edit</a>
                            
                            <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus paket ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </td>
                    </tr>
                @empty
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                            Anda belum memiliki paket harga.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($packages->hasPages())
        <div class="p-4">
            {{ $packages->links() }}
        </div>
    @endif
</div>
@endsection