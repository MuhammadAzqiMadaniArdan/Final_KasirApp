@extends('layouts.template')

@section('content')
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-flex align-items-center">
                        <li class="breadcrumb-item"><a class="link" href="{{route('dashboard')}}"><i class="mdi mdi-home-outline fs-4"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">User</li>
                    </ol>
                </nav>
                <h1 class="mb-0 fw-bold">User</h1>
            </div>
        </div>
    </div>
    @if(Session::get('success'))
    <div class="" style="padding-right: 30px;padding-left: 30px;padding-top: 20px;padding-bottom: 20px;">
        <p class="btn btn-primary" style="width: 100%;">{{Session::get('success')}}</p>
    </div>
    @endif
    @if(Session::get('failed'))
    <div class="" style="padding-right: 30px;padding-left: 30px;padding-top: 20px;padding-bottom: 20px;">
        <p class="btn btn-warning" style="width: 100%;">{{Session::get('failed')}}</p>
    </div>
    @endif

    <div class="row p-30">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-md-flex">
                        <div class="ms-auto d-flex justify-content-between">
                            @if(Auth::user()->role == "Admin")
                            <div class="dl">
                                <div class="m-r-10"><a class="btn d-flex btn-info text-white"
                                        href="{{ route('user.create') }}">Tambah User</a>
                                </div>
                            </div>
                            @endif
                            <a class="btn btn-success me-2" style="color: white;" href="{{ route('user.export.excel') }}">
                                Export Excel
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama User</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($users as $item)
                                <tr>
                                    <th scope="row">{{ $no++ }}</th>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->role }}</td>
                                    <td>
                                        <ul class="d-flex justify-content-around">
                                            <div class="m-r-10">
                                                <a class="btn btn-info text-white"
                                                    href="{{ route('user.edit', $item->id) }}">Edit</a>
                                            </div>
                                            <form action="{{ route('user.delete', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-warning">Delete</button>
                                            </form>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
