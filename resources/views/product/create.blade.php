@extends('layouts.template')

@section('content')
<div class="max-w-xl mx-auto mt-10">

    <h1 class="text-2xl font-semibold mb-6 text-center">Tambah Produk Baru</h1>
    <a href="" class="text-blue-600 hover:underline mb-6 block text-center">
        ← Kembali ke Halaman Produk
    </a>

    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data"
        class="bg-white p-6 rounded shadow-md">
        @csrf

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
            <input type="text" name="name"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Harga <span class="text-red-500">*</span></label>
            <input type="number" name="price"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Gambar Produk <span class="text-red-500">*</span></label>
            <input type="file" name="image"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <div class="mb-6">
            <label class="block font-medium text-gray-700 mb-1">Stok <span class="text-red-500">*</span></label>
            <input type="number" name="stock"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 btn btn-primary text-white font-semibold py-2 px-4 rounded transition duration-200">
            Kirim
        </button>
    </form>
</div>
@endsection
