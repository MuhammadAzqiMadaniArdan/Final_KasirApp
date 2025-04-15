@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-flex align-items-center">
                        <li class="breadcrumb-item"><a class="link"><i class="mdi mdi-home-outline fs-4"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a class="link" href="{{route('product.index')}}">Product</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Product</li>
                    </ol>
                </nav>
                <h1 class="mb-0 fw-bold">Product</h1>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <h2 class="text-center mb-4">Tambah Produk Baru</h2>

            <a href="{{ route('product.index') }}" class="btn btn-link mb-4">
                ← Kembali ke Halaman Produk
            </a>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Produk <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        </div>

                        <div class="mb-4">
                            <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stock" name="stock" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
