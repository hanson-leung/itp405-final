<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css"
        integrity="sha512-NhSC1YmyruXifcj/KFRWoC561YpHpc5Jtzgvbuzx5VozKpWvQ+4nXhPdFgmx8xqexRcpAglTj9sIBWINXa8x5w=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://www.hansonleung.co/css/style_v9.css" rel="stylesheet" crossorigin="anonymous">
    <link href="{{ url('/css/app.css') }}" rel="stylesheet" crossorigin="anonymous">
    <link rel="icon" href="https://hansonleung.co/favicon.png" type="image/png">
</head>

<body style="opacity: 1 !important">
    <section class="grid space--l">
        <nav class="card--max">
            <div class="grid__info">
                @if (Auth::check())
                    {{-- if logged in, show profile and logout links --}}
                    <div class="grid__content">
                        <a href="{{ route('index', [
                            'username' => Auth::user()->username,
                        ]) }}"
                            class="nav-link">Party central</a>
                    </div>
                    <div class="grid__content">
                        <a href="{{ route('event.create') }}" class="nav-link">Create an event</a>
                        <a href="{{ route('settings') }}" class="nav-link">Account</a>
                    </div>
                @else
                    <a href="{{ route('event.create') }}" class="nav-link">Create an event</a>
                    <a href="/login" class="nav-link">Login</a>
                @endif
            </div>
        </nav>

        <div class="card--l card--centered card__text grid__container ">
            @if ($errors->any() || session('message'))
                <div class="grid__container card--max card__text">
                    <div class="card--full">
                        @foreach ($errors->all() as $error)
                            <p class="alert txt-caution">
                                {{ $error }}
                            </p>
                        @endforeach
                        @if (session('message'))
                            <p class="alert txt-ok">
                                {{ session('message') }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            @yield('main')
        </div>

    </section>
</body>

</html>
