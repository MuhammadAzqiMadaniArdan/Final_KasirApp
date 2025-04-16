<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Struk Pembelian</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
      padding: 0;
      margin: 0;
    }
  
    .receipt-box {
      width: 100%;
      max-width: 800px;
      margin: auto;
      padding: 20px;
      background: #ffffff;
      color: #000;
    }
  
    .receipt-box h5 {
      font-size: 20px;
      font-weight: bold;
    }
  
    .receipt-box p {
      margin: 4px 0;
      font-size: 14px;
    }
  
    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      font-size: 14px;
    }
  
    .table th,
    .table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
  
    .table th {
      background-color: #f2f2f2;
    }
  
    .table-summary {
      margin-top: 10px;
      font-size: 14px;
      width: 100%;
    }
  
    .table-summary td {
      padding: 6px 4px;
    }
  
    .footer-text {
      margin-top: 30px;
      font-size: 13px;
      color: #666;
      text-align: center;
    }
  
    .thanks {
      font-weight: bold;
      text-align: center;
      margin-top: 10px;
      font-size: 14px;
    }
  
    @media print {
      body {
        background: none !important;
      }
  
      .receipt-box {
        border: none;
        box-shadow: none;
        margin: 0;
        padding: 0;
      }
  
      @page {
        size: A4;
        margin: 20mm;
      }
  
      .table,
      .table-summary {
        page-break-inside: avoid;
      }
    }
  </style>
  
</head>
<body>
@php
  $isMember = $order['customer']['status'] == "member";
  $totalPrice = 0;
@endphp

<div class="receipt-box">
  <h5>Indo April</h5>
  <p>Member Status: {{ $isMember ?  $order['customer']['member']['name'] : '-' }}</p>
  <p>No. HP: {{ $isMember ?  $order['customer']['member']['phone_number'] : '-' }}</p>
  <p>Bergabung Sejak: {{ $isMember ? \Carbon\Carbon::parse($order['customer']['member']['created_at'])->format('d F Y') : "-" }}</p>
  <p class="mb-4">Poin Member: {{ $isMember ? $order['customer']['member']['points'] : "-" }}</p>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Nama Produk</th>
        <th>Harga</th>
        <th>QTY</th>
        <th>Sub Total</th>
      </tr>
    </thead>
    <tbody>
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

  <table class="table table-summary w-100">
    <tbody>
      <tr>
        <td class="fw-bold">Total Harga</td>
        <td class="text-end fw-bold">Rp. {{ number_format($totalPrice,0,',','.') }}</td>
      </tr>
      <tr>
        <td>Poin Digunakan</td>
        <td class="text-end">Rp. {{ number_format($order['points_used'],0,',','.') }}</td>
      </tr>
      <tr>
        <td>Harga Setelah Poin</td>
        <td class="text-end">Rp. {{ number_format($totalPrice - $order['points_used'],0,',','.') }}</td>
      </tr>
      <tr>
        <td>Total Kembalian</td>
        <td class="text-end">Rp. {{ number_format($order['cost'] -  ($totalPrice - $order['points_used']),0,',','.') }}</td>
      </tr>
    </tbody>
  </table>

  <div class="footer-text">
    {{ $order['created_at'] }} | {{ $order['user']['name'] }}
  </div>
  <div class="thanks">
    Terima kasih atas pembelian Anda!
  </div>
</div>
</body>
</html>
