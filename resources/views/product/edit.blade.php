@extends('layouts.template')

@section('content')
<div class="container mt-5">
    @if (Session::get('success'))
        @include('sweetalert::alert')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="card-title text-center mb-4">Edit Produk</h1>
            <div class="text-center mb-4">
                <a href="{{ route('product.index') }}" class="text-decoration-none">
                    ← Kembali ke Halaman Produk
                </a>
            </div>

            <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ $product->name }}" class="form-control" id="name" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                    <input type="number" name="price" value="{{ $product->price }}" class="form-control" id="price" required>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Produk</label>
                    <input type="file" name="image" class="form-control" id="image">
                    @if ($product->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Gambar Produk" class="img-thumbnail" style="max-width: 150px;">
                        </div>
                    @endif
                </div>

                <div class="mb-4">
                    <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                    <input type="number" name="stock" value="{{ $product->stock }}" class="form-control bg-light" id="stock" disabled>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-warning text-white fw-semibold">
                        Update Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
