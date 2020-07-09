<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{config('app.name')}}</title>

    <!-- Bootstrap core CSS -->

    <link rel="stylesheet" href="{{asset('css/app.css')}}" type="text/css"/>
    <link rel="stylesheet" href="{{asset('css/dashboard.css')}}" type="text/css"/>
    @yield('css')
    <script src="https://kit.fontawesome.com/9e00ab1460.js" crossorigin="anonymous"></script>
</head>
<body>
<!-- Bootstrap NavBar -->
<nav class="navbar navbar-expand-md navbar-dark bg-secondary">
    <button class="navbar-toggler navbar-toggler-left d-block" type="button" data-toggle=sidebar-colapse>
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">
        <img src="https://v4-alpha.getbootstrap.com/assets/brand/bootstrap-solid.svg" width="30" height="30"
             class="d-inline-block align-top" alt="">
        <span class="">{{config('app.name')}}</span>
    </a>

    <ul class="navbar-nav m-auto d-none d-md-inline-flex">
        <li class="nav-item">
            <a class="nav-link" href="">Tags <span class="sr-only">Tags</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="">Posts</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="">Categories</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="">Newsletter</a>
        </li>
    </ul>
    <div class="dropdown">
        <button class="btn btn-link dropdown-toggle text-white" type="button" id="dropdownMenu2" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            <img src="{{config('blog.defaultPhoto')}}" width="40" height="40"
                 class="d-inline-block align-top rounded-circle" alt="">
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
            <button class="dropdown-item" type="button">Profile</button>
            <a class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                  style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</nav><!-- NavBar END -->
<nav class="nav nav-pills nav-fill  fixed-bottom  navbar-light bg-light text-center d-md-none">
    <a class="nav-item nav-link " href="">Tags </a>
    <a class="nav-item nav-link" href="">Categories</a>
    <a class="nav-item nav-link" href="">Posts</a>
    <a class="nav-item nav-link " href="">Blog Front</a>
</nav>
