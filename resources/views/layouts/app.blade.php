@include('exam::layouts.components.header')
<!-- Bootstrap row -->
<div class="row" id="body-row">
@include('exam::layouts.components.sidebar')
<!-- MAIN -->

    <div class="col px-md-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light m-0 mb-2">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                @yield('breadcrumb')
            </ol>
        </nav>
        <div class="row border-bottom border-light">
            <h3 class="col-6">@yield('header')</h3>
            <div class="col-6 text-right">
                @yield('tools')
            </div>
        </div>
        <div class="row">
            <div class="col-12 p-md-3">
                @yield('content')
            </div>
        </div>

    </div>
</div><!-- Main Col END -->
</div><!-- body-row END -->

@include('exam::layouts.components.footer')
