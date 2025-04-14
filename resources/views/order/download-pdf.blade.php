<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Receipt</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .receipt-box {
      max-width: 800px;
      margin: auto;
      padding: 30px;
      font-size: 16px;
      line-height: 24px;
      color: #333;
      background: #fff;
      border: 1px solid #eee;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
    }
    .table thead {
      background-color: #f2f2f2;
    }
    .footer-text {
      margin-top: 30px;
      font-size: 14px;
      color: #888;
    }
    .thanks {
      font-weight: bold;
      font-size: 16px;
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>
@php
$isMember = $order['customer']['status'] == "member" ? true : false;
;
@endphp
<body>
<div class="receipt-box">
  <h5 class="fw-bold">Indo April</h5>
  <p class="mb-1">Member Status :  {{ $isMember ?  $order['customer']['member']['name'] : '-'}}</p>
  <p class="mb-1">No. HP : {{ $isMember ?  $order['customer']['member']['phone_number'] : '-'}}</p>
  <p class="mb-1">Bergabung Sejak : {{ $isMember ? \Carbon\Carbon::parse($order['customer']['member']['created_at'])->format('d F Y') : "-"}}</p>
  <p class="mb-3">Poin Member : {{ $isMember ? $order['customer']['member']['points'] : "-"}}</p>

  <table class="table">
    <thead>
      <tr>
        <th>Nama Produk</th>
        <th>QTY</th>
        <th>Harga</th>
        <th>Sub Total</th>
      </tr>
    </thead>
    <tbody>
      @php
      $totalPrice = 0;
    @endphp
      @foreach ($order['order_details'] as $item)
      @php
      $subPrice = $item['product']['price'] * $item['qty'];
        $totalPrice += $subPrice;
      @endphp
      <tr>
        <td>{{ $item['product']['name'] }}</td>
        <td>Rp. {{ number_format($item['product']['price'],0,',','.') }}</td>
        <td>{{ $item['qty'] }}</td>
        <td>Rp. {{ number_format($subPrice,0,',','.') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <table class="table">
    <tbody>
      <tr>
        <td class="fw-bold">Total Harga</td>
        <td class="fw-bold text-end">Rp. {{ $totalPrice }}</td>
      </tr>
      <tr>
        <td>Poin Digunakan</td>
        <td class="text-end">{{ $order['points_used'] }}</td>
      </tr>
      <tr>
        <td>Harga Setelah Poin</td>
        <td class="text-end">Rp. {{ $totalPrice - $order['points_used'] }}</td>
      </tr>
      <tr>
        <td>Total Kembalian</td>
        <td class="text-end">Rp. {{ $order['cost'] -  ($totalPrice - $order['points_used']) }}</td>
      </tr>
    </tbody>
  </table>

  <div class="footer-text text-center">
    {{ $order['created_at'] }} | {{ $order['user']['name'] }}
  </div>
  <div class="thanks text-muted">
    Terima kasih atas pembelian Anda!
  </div>
</div>

</body>
</html>
