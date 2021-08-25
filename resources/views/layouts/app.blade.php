<!DOCTYPE html>
<html>
<head>
    <title>Simple Login System in Laravel</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
@if ($message = Session::get('error'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<nav class="navbar navbar-light bg-light">
    <span class="navbar-brand mb-0 h1">Blocks Online</span>
    @if(isset($user))
    <form class="form-inline my-2 my-lg-0">
        <a class="btn btn-outline-danger my-2 my-sm-0" id="logout" href="{{route('logout')}}" type="button">Logout</a>
    </form>
    @endif

</nav>
<div class="container">
    @yield('content')
</div>
@yield('scripts')

</body>
</html>
