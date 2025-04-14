@extends('layouts.template')
@section('content')
<div class="container">
  <div class="bg-white rounded-4 shadow p-4">
      <!-- Buttons -->
      <div class="d-flex justify-content-start mb-3">
          <a class="btn btn-primary me-2" href="{{ route('order.download.pdf',$order->id) }}">Unduh</a>
          <a class="btn btn-secondary" href="{{ route('order.index') }}">Kembali</a>
      </div>

      @php
        $isMember = $order->customer['status'] == "member" ? true : false;
        @endphp
      <!-- Info -->
      <div class="d-flex justify-content-between mb-4">
          <div>
              <h6 class="mb-1 fw-bold">{{ $isMember ? $order->customer->member->name : "Bukan Member"}}</h6>
              <p class="mb-1 text-muted" style="font-size: 0.9rem;">{{ $isMember ? \Carbon\Carbon::parse($order->customer->member->created_at)->format('d F Y') : "-"}}</p>
              <p class="mb-0 text-muted" style="font-size: 0.9rem;">MEMBER POIN : {{ $isMember ? $order->customer->member->points : "-"}}</p>
          </div>
          <div class="text-end">
              <p class="mb-1 text-muted" style="font-size: 0.9rem;">Invoice - {{ $order->invoice }}</p>
              <p class="mb-0 text-muted" style="font-size: 0.9rem;">{{ \Carbon\Carbon::parse($order->created_at)->format('d F Y') }}</p>
          </div>
      </div>

      <!-- Table Produk -->
      
      <table class="table">
        <thead>
          <tr class="text-muted">
            <th>Produk</th>
            <th>Harga</th>
            <th>Quantity</th>
            <th>Sub Total</th>
          </tr>
        </thead>
        <tbody>
          @php
            $totalPrice = 0;
          @endphp
          @foreach ($order->order_details as $item)
          @php
          $subPrice = $item->product->price * $item->qty;
            $totalPrice += $subPrice;
          @endphp
          <tr>
            <td>{{ $item->product->name }}</td>
            <td>Rp. {{ number_format($item->product->price,0,',','.') }}</td>
            <td>{{ $item->qty }}</td>
            <td>Rp. {{ number_format($subPrice,0,',','.') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <!-- Footer Total -->
      <div class="row rounded-3 py-3 px-2" style="background-color: #f1f3f5;">
          <div class="col-md-3">
              <p class="text-muted mb-0" style="font-size: 0.8rem;">POIN DIGUNAKAN</p>
              <p class="mb-0">{{ $order->points_used }}</p>
          </div>
          <div class="col-md-3">
              <p class="text-muted mb-0" style="font-size: 0.8rem;">KASIR</p>
              <p class="mb-0">{{ $order->user->name }}</p>
          </div>
          <div class="col-md-3">
              <p class="text-muted mb-0" style="font-size: 0.8rem;">KEMBALIAN</p>
              <p class="mb-0 fw-bold">Rp. {{ number_format($order->cost - $totalPrice,0,',','.') }}</p>
          </div>
          <div class="col-md-3 d-flex flex-column justify-content-center align-items-center p-3 rounded-3" style="background-color: #212529; color: white;">
              <span style="font-size: 0.8rem; text-transform: uppercase;">Total</span>
              @php
               $pointsUsed = $order->points_used == 0 ? false : true;
              @endphp
              @if($pointsUsed == true)
              <strong style="font-size: 1.25rem;">Rp. <s>{{ number_format($totalPrice,0,',','.') }}</s></strong>
              @endif
              <strong style="font-size: 1.25rem;">Rp. {{ number_format($totalPrice - $order->points_used,0,',','.') }}</strong>
          </div>
      </div>
  </div>
</div>
@endsection