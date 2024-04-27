<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css"
        integrity="sha512-NhSC1YmyruXifcj/KFRWoC561YpHpc5Jtzgvbuzx5VozKpWvQ+4nXhPdFgmx8xqexRcpAglTj9sIBWINXa8x5w=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://www.hansonleung.co/css/style_v9.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="icon" href="https://hansonleung.co/favicon.png" type="image/png">
</head>

<body style="opacity: 1 !important">
    <section class="grid space--l">
        <nav class="card--max">
            <div class="grid__info">
                @if (Auth::check())
                    {{-- if logged in, show profile and logout links --}}
                    <a href="{{ route('index', [
                        'username' => Auth::user()->username,
                    ]) }}"
                        class="nav-link">Party central</a>
                    <a href="{{ route('settings') }}" class="nav-link">Account</a>
                @else
                    <a href="/login" class="nav-link">Login</a>
                @endif
            </div>
        </nav>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('main')

        @if ($errors->any())
            <div class="grid__container card--m errors card__text">
                <div class="card--full">
                    @foreach ($errors->all() as $error)
                        <p class="alert">
                            {{ $error }}
                        </p>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
</body>

</html>
