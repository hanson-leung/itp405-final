<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-3">
        <ul class="nav d-flex justify-content-end">
            @if (Auth::check())
                {{-- if logged in, show profile and logout links --}}
                <li class="nav-item">
                    <a href="{{ route('index', [
                        'username' => Auth::user()->username,
                    ]) }}"
                        class="nav-link">Party Central</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings') }}" class="nav-link">Settings</a>
                </li>
                <li class="nav-item">
                    <form method="post" action="{{ route('logout.post') }}">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link">Logout</button>
                    </form>
                </li>
            @else
                <li class="nav-item">
                    <a href="/login" class="nav-link">Login</a>
                </li>
            @endif
        </ul>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('main')
    </div>
</body>

</html>
