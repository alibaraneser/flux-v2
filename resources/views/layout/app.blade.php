<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
    <link rel="stylesheet" href="{!! asset('dist/css/style.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('dist/css/easy-autocomplete.themes.min.css') !!}">
    <title>Music</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body class="dark">
<header class="header">
    @include('inc.header')
    @include('inc.errorSuccess')
    <div class="header__right">
        <div class="header__right-container">
            <div class="header__search-container" style="position: relative">
                <div class="header__search">
                    <input class="header__search-input" type="text" id="searchBox" placeholder="Search Artist">
                    <button class="header__search-icon" id="search">
                        <img src="{!! asset('img/search-icon.png') !!}">
                    </button>
                </div>
                <span style="padding-left: 25px; color:#C3C3C4;" id="result">

                </span>
            </div>
            <div class="header__mode">
                <img src="{!! asset('img/sunny.png') !!}">
            </div>
            {{--@if(Auth::check())--}}
                {{--<a class="header__menu-item" href="#">Control Panel</a>--}}
            {{--@endif--}}
        </div>
    </div>
</header>

@yield('content')

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="{!! asset('dist/js/bundle.js') !!}"></script>
<script src="{!! asset('dist/js/jquery.easy-autocomplete.min.js') !!}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

@stack('script')

@include('inc.search')
</body>

</html>
