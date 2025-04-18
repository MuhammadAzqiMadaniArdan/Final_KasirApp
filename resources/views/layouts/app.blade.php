<!DOCTYPE html>
<html lang="en" dir="ltr">
@include('layouts.head')
<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
         data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

        @include('layouts.navbar')
        @include('layouts.sidebar')

        <div class="page-wrapper">
            <div class="container-fluid">
                @yield('content')  {{-- Tempat konten halaman --}}
            </div>
        </div>
    </div>

    @include('layouts.scripts')
</body>
</html>
