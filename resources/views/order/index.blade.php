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
                <h1 class="mb-0 fw-bold">Orders</h1>
            </div>
        </div>
    </div>
    @if (Session::get('failed'))
        <p>{{ Session::get('failed') }}</p>
    @endif
    <div class="row p-30">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-md-flex">
                        <div class="ms-auto">
                            <div class="d-flex">
                                <div class="m-r-10">
                                    @if($date == "daily")

                                    <a class="btn d-flex btn-success text-white"
                                    href="{{ route('order.export.excel') }}">Export Excel Harian</a>
                                    @elseif ($date == "monthly")
                                    <a class="btn d-flex btn-success text-white"
                                    href="{{ route('order.export.excel.month') }}">Export Excel Bulanan</a>
                                    @else
                                    <a class="btn d-flex btn-success text-white"
                                    href="{{ route('order.export.excel.year') }}">Export Excel Tahunan</a>
                                    @endif                                    
                                </div>
                                <div class="m-r-10"><a class="btn d-flex btn-info text-white"
                                        href="{{ route('order.cart') }}">Tambah Produk</a>
                                </div>
                            </div>
                        </div>
                        <form action="{{route('order.index')}}" method="GET">
                            <select name="date" id="date" onChange="this.form.submit()">
                                <option value="daily" {{ $date == "daily" ? "selected" : "" }}>Harian</option>
                                <option value="monthly" {{ $date == "monthly" ? "selected" : "" }}>Bulanan</option>
                                <option value="year" {{ $date == "year" ? "selected" : "" }}>Tahunan</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Customer</th>
                                <th scope="col">Tanggal Penjualan</th>
                                <th scope="col">Total Harga</th>
                                <th scope="col">Petugas</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($orders as $item)
                                @php
                                    $isMember = $item->customer->status == 'member' ? true : false;
                                @endphp
                                <tr>
                                    <th scope="row">{{ $no++ }}</th>
                                    <td>{{ $isMember == true ? $item->customer->member->name : 'Bukan Member' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}</td>
                                    <td>{{ $item->total_price - $item->points_used }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>
                                        <ul>
                                            <div class="m-r-10">
                                                <a class="btn btn-info text-white" href="">Lihat</a>
                                            </div>
                                            <div class="m-r-10">
                                                <a class="btn btn-info text-white"
                                                    href="{{ route('order.download.pdf', $item->id) }}">Download PDF</a>
                                            </div>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
