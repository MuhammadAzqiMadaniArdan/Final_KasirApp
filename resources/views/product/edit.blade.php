@extends('layouts.template')

@section('content')
<div class="max-w-xl mx-auto mt-10">
    @if (Session::get('success'))
        @include('sweetalert::alert')
        <div class="mb-4 p-3 rounded bg-green-500 text-white">
            {{ Session::get('success') }}
        </div>
    @endif

    <h1 class="text-2xl font-semibold mb-6 text-center">Edit Produk</h1>
    <a href="" class="text-blue-600 hover:underline mb-6 block text-center">
        ← Kembali ke Halaman Produk
    </a>

    <form action="{{ route('product.update',$product->id) }}" method="POST" enctype="multipart/form-data"
        class="bg-white p-6 rounded shadow-md">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ $product->name }}"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" >
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Harga <span class="text-red-500">*</span></label>
            <input type="number" name="price" value="{{ $product->price }}"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" >
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Gambar Produk</label>
            <input type="file" name="image"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">

            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" alt="Gambar Produk" class="w-32 h-32 object-cover mt-2 rounded shadow">
            @endif
        </div>

        <div class="mb-6">
            <label class="block font-medium text-gray-700 mb-1">Stok <span class="text-red-500">*</span></label>
            <input type="number" name="stock" value="{{ $product->name }}"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" disabled>
        </div>

        <button type="submit"
            class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded transition duration-200">
            Update Produk
        </button>
    </form>
</div>
@endsection
