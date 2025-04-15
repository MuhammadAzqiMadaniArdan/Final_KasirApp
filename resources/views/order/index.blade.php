@extends('layouts.template')

@section('content')
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-flex align-items-center">
                        <li class="breadcrumb-item">
                            <a class="link"><i class="mdi mdi-home-outline fs-4"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Product</li>
                    </ol>
                </nav>
                <h1 class="mb-0 fw-bold">Orders</h1>
            </div>
        </div>
    </div>

    @if (Session::get('failed'))
        <p class="text-danger">{{ Session::get('failed') }}</p>
    @endif

    <div class="row p-30">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-md-flex">
                        @if(Auth::user()->role == "Employee")
                        <div class="ms-auto d-flex">
                            <div class="me-2">
                                <a class="btn btn-info text-white" href="{{ route('order.cart') }}">Tambah Penjualan</a>
                            </div>
                        </div>
                        @endif

                        <form action="{{ route('order.export.excel') }}" method="GET" class="me-3">
                            <input type="hidden" name="type" value="{{ $date }}">
                            <input type="hidden" name="month" value="{{ $month }}">
                            <input type="hidden" name="year" value="{{ $year }}">
                            <button type="submit" class="btn btn-primary">Export Excel</button>
                        </form>

                        <form action="{{ route('order.index') }}" method="GET" class="d-flex">
                            <div class="me-2">
                                <select class="form-select" name="limit" onchange="this.form.submit()">
                                    <option value="10" {{ $limit == '10' ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $limit == '25' ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $limit == '50' ? 'selected' : '' }}>50</option>
                                </select>
                            </div>

                            <div class="me-2">
                                <select class="form-select" name="date" onchange="this.form.submit()">
                                    <option value="daily" {{ $date == 'daily' ? 'selected' : '' }}>Harian</option>
                                    <option value="monthly" {{ $date == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                    <option value="year" {{ $date == 'year' ? 'selected' : '' }}>Tahunan</option>
                                </select>
                            </div>

                            @if ($date == 'monthly')
                                <div class="me-2">
                                    <select class="form-select" name="month" onchange="this.form.submit()">
                                        @foreach ($months as $key => $m)
                                            <option value="{{ $key }}" {{ request('month') == $key ? 'selected' : '' }}>
                                                {{ $m }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if (in_array($date, ['monthly', 'year']))
                                <div>
                                    <select class="form-select" name="year" onchange="this.form.submit()">
                                        @for ($y = now()->year; $y >= 2020; $y--)
                                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Customer</th>
                                <th>Tanggal Penjualan</th>
                                <th>Total Harga</th>
                                <th>Petugas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $index => $item)
                                @php
                                    $isMember = $item->customer->status == 'member';
                                    $member = $item->customer->member ?? null;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $isMember ? $member->name : 'Bukan Member' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</td>
                                    <td>Rp. {{ number_format($item->total_price - $item->points_used, 0, ',', '.') }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td class="d-flex justify-content-around">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#orderModal{{ $item->id }}">
                                            Lihat
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="orderModal{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="orderModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="orderModalLabel{{ $item->id }}">
                                                            Detail Pesanan
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Status Member:</strong> {{ $isMember ? $member->name : '-' }}</p>
                                                        <p><strong>No. HP:</strong> {{ $isMember ? $member->phone_number : '-' }}</p>
                                                        <p><strong>Bergabung Sejak:</strong>
                                                            {{ $isMember ? $member->created_at->translatedFormat('d F Y') : '-' }}</p>
                                                        <p><strong>Poin Member:</strong> {{ $isMember ? $member->points : '-' }}</p>

                                                        <table class="table table-bordered mt-3">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nama Produk</th>
                                                                    <th>Harga</th>
                                                                    <th>QTY</th>
                                                                    <th>Sub Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php $totalPrice = 0; @endphp
                                                                @foreach ($item->order_details as $detail)
                                                                    @php
                                                                        $subPrice = $detail->product->price * $detail->qty;
                                                                        $totalPrice += $subPrice;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{ $detail->product->name }}</td>
                                                                        <td>Rp. {{ number_format($detail->product->price, 0, ',', '.') }}</td>
                                                                        <td>{{ $detail->qty }}</td>
                                                                        <td>Rp. {{ number_format($subPrice, 0, ',', '.') }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                        <table class="table">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="fw-bold">Total Harga</td>
                                                                    <td class="text-end fw-bold">Rp. {{ number_format($totalPrice, 0, ',', '.') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Poin Digunakan</td>
                                                                    <td class="text-end">{{ $item->points_used }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Harga Setelah Poin</td>
                                                                    <td class="text-end">Rp. {{ number_format($totalPrice - $item->points_used, 0, ',', '.') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Total Kembalian</td>
                                                                    <td class="text-end">Rp. {{ number_format($item->cost - ($totalPrice - $item->points_used), 0, ',', '.') }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                        <div class="text-center">
                                                            <small>{{ $item->created_at->format('d-m-Y H:i') }} | {{ $item->user->name }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <a class="btn btn-warning me-2" href="{{ route('order.download.pdf',$item->id) }}">Unduh</a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
