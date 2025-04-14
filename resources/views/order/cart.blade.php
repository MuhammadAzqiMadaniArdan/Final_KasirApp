@extends('layouts.template')

@section('content')
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-flex align-items-center">
                        <li class="breadcrumb-item"><a href="" class="link"><i
                                    class="mdi mdi-home-outline fs-4"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order</li>
                    </ol>
                </nav>
                <h1 class="mb-0 fw-bold">Order</h1>
            </div>
        </div>
    </div>
    <div class="row p-30">
        <div class="col-12">
            <form action="{{ route('order.cart.post') }}" method="POST">
                @csrf
                <div style="display: flex;gap:10px;">

                    @foreach ($products as $index => $item)
                        <div class="product card" style="width: 30%">
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" width="100">
                            <div class="card-body">
                                <h3>{{ $item->name }}</h3>
                                <p class="price" data-raw="{{ $item->price }}">Rp.
                                    {{ number_format($item->price, 0, ',', '.') }}</p>
                                <p>{{ $item->stock }}</p>
                                <div class="d-md-flex">
                                    <div class="m-r-10">
                                        <button class="btn-minus btn btn-info text-white" type="button">-</buttom>
                                    </div> <input type="number" name="qty[{{ $index }}]" class="qty"
                                        value="{{ $item->qty == 0 ? '0' : '' }}" min="0" max="{{ $item->stock }}">
                                    <div class="m-r-10">
                                        <button class="btn-plus btn btn-info text-white" type="button" >+</buttom>
                                    </div> 
                                    {{-- <input type="number" name="qty[{{ $index }}]" class="qty"
                                        value="{{ $item->qty == 0 ? '0' : '' }}" min="0" max="{{ $item->stock }}">
                                    <button type="button" class="btn-plus">+</button> --}}
                                </div>
                                <h2>Total <span class="total">Rp. 0</span></h2>
                                <input type="hidden" name="id[{{ $index }}]" value="{{ $item->id }}">
                                <input type="hidden" name="stock[{{ $index }}]" value="{{ $item->stock }}"
                                    class="stock">
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="submit">
                    checkout
                </button>
            </form>
        </div>

    </div>
    @push('script')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const updateTotal = (productCard) => {
                    const price = parseInt(productCard.querySelector(".price").dataset.raw);
                    const qty = parseInt(productCard.querySelector(".qty").value);
                    const totalSpan = productCard.querySelector(".total");
                    const total = price * qty;
                    totalSpan.textContent = total;
                    console.log(qty)
                }

                document.querySelectorAll('.product').forEach(productCard => {
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
                            alert('stock Habis');
                        }
                    })

                    btnMinus.addEventListener('click', () => {
                        let currentQty = parseInt(qtyInput.value) || 0;
                        if (currentQty > 0) {
                            qtyInput.value = currentQty - 1;
                            updateTotal(productCard);
                        }
                    });

                    updateTotal(productCard);

                });
            })
        </script>
    @endpush
@endsection
