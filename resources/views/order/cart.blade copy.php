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
            <div class="card">
                <div class="card-body">
                    <div class="d-md-flex">
                        <div class="ms-auto">
                            <div class="dl">
                                <div class="m-r-10"><a class="btn d-flex btn-info text-white"
                                        href="">Tambah Order</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Pelanggan</th>
                                <th scope="col">Tanggal Penjualan</th>
                                <th scope="col">Total Harga</th>
                                <th scope="col">Dibuat Oleh</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <th scope="row"></th>
                                    <td>
                                    </td>
                                    <td></td>
                                    <td>Rp </td>
                                    <td>nama user</td>
                                    <td>
                                        <ul>
                                            <div class="m-r-10">
                                                <a class="btn btn-info text-white"
                                                    href="">Edit</a>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modalOrderorderid">
                                                    Detail
                                                </button>

                                                <div class="modal fade" id="modalOrderorderid" tabindex="-1"
                                                    aria-labelledby="modalLabelorderid" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalLabelorderid">
                                                                    Detail Order</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p><strong>Nama Pelanggan:</strong>
                                                                </p>
                                                                <p><strong>Tanggal Penjualan:</strong>
                                                                <p><strong>Total Harga:</strong> Rp
                                                                </p>
                                                                <p><strong>Dibuat Oleh:</strong> 
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <form action="" method="POST">
                                                <button type="submit" class="btn btn-info text-white">
                                                    Delete
                                                </button>
                                            </form>
                                        </ul>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection