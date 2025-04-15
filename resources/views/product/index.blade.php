@extends('layouts.template')

@section('content')
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-flex align-items-center">
                        <li class="breadcrumb-item"><a class="link"><i class="mdi mdi-home-outline fs-4"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Product</li>
                    </ol>
                </nav>
                <h1 class="mb-0 fw-bold">Product</h1>
            </div>
        </div>
    </div>
    @if(Session::get('success'))
    <div class="" style="padding-right: 30px;padding-left: 30px;padding-top: 20px;padding-bottom: 20px;">
        <p class="btn btn-primary" style="width: 100%;">{{Session::get('success')}}</p>
    </div>
    @endif
    @if(Session::get('failed'))
    <div class="" style="padding-right: 30px;padding-left: 30px;padding-top: 20px;padding-bottom: 20px;">
        <p class="btn btn-warning" style="width: 100%;">{{Session::get('failed')}}</p>
    </div>
    @endif
    <div class="row p-30">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-md-flex">
                        <div class="ms-auto">
                            @if(Auth::user()->role == "Admin")
                            <div class="dl">
                                <div class="m-r-10"><a class="btn d-flex btn-info text-white"
                                        href="{{ route('product.create') }}">Tambah Produk</a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col"></th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Stok</th>
                                @if(Auth::user()->role == "Admin")
                                <th scope="col"></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($products as $item)
                                <tr>
                                    <th scope="row">{{ $no++ }}</th>
                                    <td><img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                            width="100"></td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->price }}</td>
                                    <td>{{ $item->stock }}</td>
                                    @if(Auth::user()->role == "Admin")
                                    <td>
                                        <ul class="d-flex justify-content-between">
                                            <div class="m-r-10">
                                                <a class="btn btn-info text-white"
                                                    href="{{ route('product.edit', $item->id) }}">Edit</a>
                                            </div>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#orderModal{{ $item->id }}">
                                                Update Stock
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="orderModal{{ $item->id }}" tabindex="-1"
                                                aria-labelledby="orderModal{{ $item->id }}Label" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="orderModal{{ $item->id }}Label">Modal title</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('product.updateStock', $item->id) }}"
                                                            method="POST" class="container mt-3">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="mb-3">
                                                                <label for="name" class="form-label">Nama </label>
                                                                <input type="text" class="form-control" id="name"
                                                                    name="name" value="{{ $item->name }}" disabled>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="name" class="form-label">Stock </label>
                                                                <input type="number" class="form-control" id="stock"
                                                                    name="stock" value="{{ $item->stock }}"
                                                                    value="{{ $item->stock }}" min="0">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save
                                                                    changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <form action="{{ route('product.delete', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-warning">Delete</button>
                                            </form>
                                        </ul>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
