@extends('layouts.template')

@section('content')
    <!-- Button trigger modal -->
    {{-- <button type="button" class="btn btn-primary m-10" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Launch demo modal
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </nav> --}}

@if(Auth::user()->role == "Employee")
<div class="d-flex justify-content-center">

    <div class="card" style="width: 80%;padding:10px;text-align:center;">
        <div>
            <h1>Halo, {{ Auth::user()->name }}</h1>
        </div>
        <div class="card-header">
            Data Pembelian Hari ini
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">{{ $ordersToday }}</li>
        </ul>
        <div class="card-header">
            
        </div>
    </div>
</div>
    
    {{-- <form>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form> --}}
    {{-- 
    <label for="inputPassword5" class="form-label">Password</label>
    <input type="password" id="inputPassword5" class="form-control" aria-describedby="passwordHelpBlock">
    <div id="passwordHelpBlock" class="form-text">
        Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special
        characters, or emoji.
    </div>
    
    <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
        <option selected>Open this select menu</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
    </select>
    
    <select class="form-select form-select-sm" aria-label=".form-select-sm example">
        <option selected>Open this select menu</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
    </select>
    
    <form class="bg-red-300">
        <fieldset disabled>
            <legend>Disabled fieldset example</legend>
            <div class="mb-3">
                <label for="disabledTextInput" class="form-label">Disabled input</label>
                <input type="text" id="disabledTextInput" class="form-control" placeholder="Disabled input">
            </div>
            <div class="mb-3">
                <label for="disabledSelect" class="form-label">Disabled select menu</label>
                <select id="disabledSelect" class="form-select">
                    <option>Disabled select</option>
                </select>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="disabledFieldsetCheck" disabled>
                    <label class="form-check-label" for="disabledFieldsetCheck">
                        Can't check this
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </fieldset>
    </form> --}}
    
    
    @else
    <div class="container">
        <h1 class="text-2xl font-bold mb-4">Selamat Datang, {{ auth()->user()->name }}!</h1>
        
        <div class="grid grid-cols-2 grid-rows-2 md:grid-cols-2 gap-8">
            <div style="width: 100%;">
                {!! $barChart->container() !!}
            </div>
            <div class="width:10%;">
                {!! $pieChart->container() !!}
            </div>
        </div>
    </div>

    
    @endif
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    {!! $barChart->script() !!}
    {!! $pieChart->script() !!}

    @endsection
    @push('script')
    @endpush
