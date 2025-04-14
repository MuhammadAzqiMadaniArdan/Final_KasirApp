@extends('layouts.template')

@section('content')
    <div class="container py-4">
        <h2 class="fw-bold mb-4">Keranjang Anda</h2>
        {{-- kalau cart kosong --}}
        @if ($carts->isEmpty())
            <p class="text-muted">Tidak ada item di keranjang.</p>
        @else
            {{-- nggak kosong --}}
            <form action="{{ route('order.store') }}" method="POST">
                @csrf
                <div class="row">
                    {{-- Produk yang dipilih --}}
                    <div class="col-md-6 mb-4">
                        <div class="card p-4">
                            <h5 class="fw-semibold mb-3">Produk yang Dipilih</h5>
                            @php
                                $totalPrice = 0;
                            @endphp
                            @foreach ($carts as $item)
                                @php
                                $subTotal = $item->product->price * $item->qty;
                                    $totalPrice += $subTotal;
                                @endphp
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <p class="mb-0">{{ $item->product->name }}</p>
                                        <small class="text-muted">Rp. {{ number_format($item->qty, 0, ',', '.') }} x
                                        </small>
                                    </div>
                                    <p class="fw-semibold mb-0">Rp. {{ number_format($item->product->price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <input type="hidden" name="products[{{ $loop->index }}][product_id]"
                                    value="{{ $item->product->id }}">
                                <input type="hidden" name="products[{{ $loop->index }}][qty]" value="{{ $item->qty }}">
                                <input type="hidden" name="products[{{ $loop->index }}][price]"
                                    value="{{ $item->product->price }}">
                            @endforeach
                            <hr>

                            <div class="d-flex justify-content-between">
                                <h6 class="mb-0">Total</h6>
                                <h6 class="fw-bold text-primary mb-0">Rp. {{ number_format($totalPrice, 0, ',', '.') }}
                                </h6>
                            </div>
                        </div>
                    </div>

                    {{-- Form Pembayaran --}}
                    <div class="col-md-6">
                        <div class="card p-4">
                            <div class="mb-3">
                                <label class="form-label">Member Status <span class="text-danger small">(Dapat juga membuat
                                        member)</span></label>
                                <select name="status" id="status" class="form-select" onchange="toogleNotelp()">
                                    <option value="non-member" selected>Bukan Member</option>
                                    <option value="member">Member</option>
                                </select>
                            </div>

                            <div id="phoneNumber-wrapper" class="mb-3" style="display: none;">
                                <label for="phone_number" class="form-label">No Telp <span
                                        class="text-danger small">(daftar/gunakan member)</span></label>
                                <input type="tel" name="phone_number" id="phone_number" class="form-control"
                                    maxlength="13" placeholder="08xxxxxxxxxx">
                            </div>

                            <div class="mb-3">
                                <label for="cost" class="form-label">Total Bayar</label>
                                <input type="number" name="cost" id="cost" class="form-control" value=""
                                    min="{{ $totalPrice }}" max="99999999999999999999"  onchange="totalHarga()" maxlength="11">
                                <span id="alert-cost" class="text-danger small" style="display:none;">Jumlah bayar
                                    kurang</span>
                            </div>

                            <input type="hidden" name="total_price" id="total_price" value="{{ $totalPrice }}">

                            <button type="submit" id="submit-btn" class="btn btn-primary w-100">Pesan</button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <script>
        function toogleNotelp() {
            const member = document.getElementById('status');
            const notelp = document.getElementById('phoneNumber-wrapper');

            if (member.value == "member") {
                notelp.style.display = "block"
            } else {
                notelp.style.display = "none"
            }
        }

        function totalHarga() {
            const alertPrice = document.getElementById('alert-cost');
            const submitBtn = document.getElementById('submit-btn');
            const cost = Number(document.getElementById('cost').value);
            const totalPrice = Number(document.getElementById('total_price').value);
            if (cost < totalPrice) {
                alertPrice.style.display = 'block';
                submitBtn.disabled = true;
            } else {
                alertPrice.style.display = 'none';
                submitBtn.disabled = false;
            }
        }

        window.onload = function() {
            toogleNotelp();
            totalHarga();
        }
    </script>
@endsection
