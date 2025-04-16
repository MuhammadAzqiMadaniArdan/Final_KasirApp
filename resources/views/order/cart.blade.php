@extends('layouts.template')

@section('content')
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 d-flex align-items-center">
                    <li class="breadcrumb-item">
                        <a href="" class="link"><i class="mdi mdi-home-outline fs-4"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Order</li>
                </ol>
            </nav>
            <h1 class="mb-0 fw-bold">Order</h1>
        </div>
    </div>
</div>

<div class="row py-4">
    <div class="col-12">
        <form action="{{ route('order.cart.post') }}" method="POST">
            @csrf
            <div class="row p-4">
                @foreach ($products as $index => $item)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 d-flex justify-content-center items-content-center" style="text-align: center;">
                            <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top img-fluid" alt="{{ $item->name }}" width="150">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $item->name }}</h5>
                                <p class="card-text mb-1">
                                    <strong>Harga:</strong> 
                                    <span class="price" data-raw="{{ $item->price }}">Rp. {{ number_format($item->price, 0, ',', '.') }}</span>
                                </p>
                                <p class="card-text mb-2"><strong>Stok:</strong> {{ $item->stock }}</p>
                                <div class="d-flex align-items-center mb-3" style="display: flex;justify-content: center;">
                                    <button type="button" class="btn-minus btn btn-info text-white btn-sm me-2">-</button>
                                    <input type="number" name="qty[{{ $index }}]" class="form-control qty text-center" value="{{ $item->qty == 0 ? '0' : '' }}" min="0" max="{{ $item->stock }}" style="width: 60px;">
                                    <button type="button" class="btn-plus btn btn-info text-white btn-sm ms-2">+</button>
                                </div>
                                <p><strong>Total:</strong> <span class="total">Rp. 0</span></p>

                                <input type="hidden" name="id[{{ $index }}]" value="{{ $item->id }}">
                                <input type="hidden" name="stock[{{ $index }}]" value="{{ $item->stock }}" class="stock">

                                {{-- Tambahan jika ingin tombol di dalam card --}}
                                {{-- <button class="btn btn-primary mt-auto">Tambah ke Keranjang</button> --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-end mt-3 px-3">
                <button type="submit" class="btn btn-success px-4 py-2" style="color: white;display: block;position: absolute;">Checkout</button>
            </div>
        </form>
    </div>
</div>

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const updateTotal = (productCard) => {
            const price = parseInt(productCard.querySelector(".price").dataset.raw);
            const qty = parseInt(productCard.querySelector(".qty").value);
            const totalSpan = productCard.querySelector(".total");
            const total = price * qty;
            totalSpan.textContent = `Rp. ${total.toLocaleString("id-ID")}`;
        }

        document.querySelectorAll('.card').forEach(productCard => {
            const btnPlus = productCard.querySelector('.btn-plus');
            const btnMinus = productCard.querySelector('.btn-minus');
            const qtyInput = productCard.querySelector('.qty');
            const stock = parseInt(productCard.querySelector('.stock').value);

            btnPlus.addEventListener('click', () => {
                let currentQty = parseInt(qtyInput.value) || 0;
                if (currentQty < stock) {
                    qtyInput.value = currentQty + 1;
                    updateTotal(productCard);
                } else {
                    alert('Stok habis!');
                }
            });

            btnMinus.addEventListener('click', () => {
                let currentQty = parseInt(qtyInput.value) || 0;
                if (currentQty > 0) {
                    qtyInput.value = currentQty - 1;
                    updateTotal(productCard);
                }
            });

            qtyInput.addEventListener('input', () => {
                let val = parseInt(qtyInput.value);
                if (isNaN(val) || val < 0) qtyInput.value = 0;
                else if (val > stock) qtyInput.value = stock;
                updateTotal(productCard);
            });

            updateTotal(productCard);
        });
    });
</script>
@endpush
@endsection
