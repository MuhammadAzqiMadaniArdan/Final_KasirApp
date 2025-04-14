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
$isMember = $order->customer->member == "member" ? true : false;
@endphp
<body>
<div class="receipt-box">
  <h5 class="fw-bold">Indo April</h5>
  <p class="mb-1">Member Status :  </p>
  <p class="mb-1">No. HP : 081111111111</p>
  <p class="mb-1">Bergabung Sejak : 08 February 2025</p>
  <p class="mb-3">Poin Member : 120000</p>

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
      <tr>
        <td>TV</td>
        <td>2</td>
        <td>Rp. 6.000.000</td>
        <td>Rp. 12.000.000</td>
      </tr>
    </tbody>
  </table>

  <table class="table">
    <tbody>
      <tr>
        <td class="fw-bold">Total Harga</td>
        <td class="fw-bold text-end">Rp. 12.000.000</td>
      </tr>
      <tr>
        <td>Poin Digunakan</td>
        <td class="text-end">0</td>
      </tr>
      <tr>
        <td>Harga Setelah Poin</td>
        <td class="text-end">Rp. 0</td>
      </tr>
      <tr>
        <td>Total Kembalian</td>
        <td class="text-end">Rp. 0</td>
      </tr>
    </tbody>
  </table>

  <div class="footer-text text-center">
    2025-02-08T05:59:57.000000Z | Petugas
  </div>
  <div class="thanks text-muted">
    Terima kasih atas pembelian Anda!
  </div>
</div>

</body>
</html>
