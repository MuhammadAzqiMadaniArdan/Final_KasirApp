@extends('layouts.template')

@section('content')
    <div class="container mt-5">
        @if (Session::get('Success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('Success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (Session::get('Error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ Session::get('Error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title mb-4">Keranjang Anda</h2>

                <p class="text-muted">Tidak ada item di keranjang.</p>
                <form action="{{ route('order.member.store',$member['id']) }}" method="POST">
                    @csrf
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalPrice = 0;
                                @endphp
                                @foreach ($carts as $item)
                                    @php
                                        $subTotal = $item->product->price * $item->qty;
                                        $totalPrice += $subTotal;
                                    @endphp
                                    <tr>

                                        <td class="mb-0">{{ $item->product->name }}</td>
                                        <td class="text-muted">{{ number_format($item->qty, 0, ',', '.') }}
                                        </td>
                                        <td class="fw-semibold mb-0">Rp.
                                            {{ number_format($item->product->price, 0, ',', '.') }}
                                        </td>
                                        <td class="fw-semibold mb-0">Rp.
                                            {{ number_format($subTotal, 0, ',', '.') }}
                                        </td>
                                        <input type="hidden" name="products[{{ $loop->index }}][product_id]"
                                        value="{{ $item->product->id }}">
                                        <input type="hidden" name="products[{{ $loop->index }}][qty]"
                                        value="{{ $item->qty }}">
                                        <input type="hidden" name="products[{{ $loop->index }}][price]"
                                        value="{{ $item->product->price }}">
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Harga Keseluruhan:</strong></td>
                                    <td><strong>Rp {{ number_format($totalPrice, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Harga Bayar:</strong></td>
                                    <td><strong>Rp {{ number_format($customer->total_payment,0,',','.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Member <span
                                class="text-danger">(Identitas)</span></label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $member->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="points" class="form-label">Poin <span>(daftar/gunakan member)</span></label>
                        <input type="number" name="points" id="points" class="form-control" value="{{ $member->points }}" disabled>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="hidden" id="points_check" name="points_checked" value="0">
                        <input class="form-check-input" type="checkbox" id="points_check" name="points_checked" value="1" {{ $member->status == "new" ? "disabled" : ""}}>
                        <label class="form-check-label" for="points_check">
                            Gunakan poin <span class="text-danger">Poin tidak dapat digunakan pada pembelanjaan
                                pertama.</span>
                        </label>
                    </div>

                    <input type="hidden" name="total_price" value="{{ $totalPrice }}">
                    <input type="hidden" name="cost" value="{{ $customer->total_payment }}">
                    <input type="hidden" name="status" value="member">

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                {{-- @endif --}}
            </div>
        </div>
    </div>
@endsection
