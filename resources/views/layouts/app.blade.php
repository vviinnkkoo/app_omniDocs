<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script type="module" src="{{ asset('js/custom.js') }}" defer></script>
</head>
<body>

<!-- Back to top button -->
<button type="button" class="btn btn-danger btn-floating btn-lg" id="btn-back-to-top"><i class="bi bi-arrow-up"></i></button>

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @guest
                        <!-- Do nothing -->
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Postavke
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/kupci"><i class="bi bi-person-add"></i>&nbsp;&nbsp;Kupci</a>
                                    <a class="dropdown-item" href="/dostavne-usluge"><i class="bi bi-box2"></i>&nbsp;&nbsp;Dostavne usluge</a>
                                    <a class="dropdown-item" href="/nacin-placanja"><i class="bi bi-cash-coin"></i> Način plaćanja</a>
                                    <a class="dropdown-item" href="/radne-godine"><i class="bi bi-server"></i> Radne godine</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/kanali-prodaje"><i class="bi bi-funnel"></i> Kanali prodaje</a>
                                    <a class="dropdown-item" href="/drzave-poslovanja"><i class="bi bi-geo-alt-fill"></i> Države poslovanja</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/proizvodi"><i class="bi bi-suit-club"></i> Proizvodi</a>
                                    <a class="dropdown-item" href="/vrste-proizvoda"><i class="bi bi-tags"></i> Vrsta proizvoda</a>
                                    <a class="dropdown-item" href="/opis"><i class="bi bi-palette"></i> Boje proizvoda</a>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Narudžbe
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/narudzbe/prikaz/sve"><i class="bi bi-list-stars"></i> Popis narudžbi</a>
                                    <a class="dropdown-item" href="/narudzbe/prikaz/poslane"><i class="bi bi-car-front-fill"></i> Poslane narudžbe</a>
                                    <a class="dropdown-item" href="/narudzbe/prikaz/neodradene"><i class="bi bi-exclamation-diamond-fill"></i> Neodrađene narudžbe</a>
                                    <a class="dropdown-item" href="/narudzbe/prikaz/otkazane"><i class="bi bi-x-octagon"></i> Otkazane narudžbe</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/proizvodi/prikaz/u-izradi"><i class="bi bi-gear-fill"></i> Proizvodi u izradi</a>
                                    <a class="dropdown-item" href="/proizvodi/prikaz/grupirano-prema-boji"><i class="bi bi-gear-fill"></i> U izradi po boji</a>
                                    <a class="dropdown-item" href="/proizvodi/prikaz/grupirano-u-izradi"><i class="bi bi-gear-fill"></i> Grupirani proizvodi u izradi</a>
                                    <a class="dropdown-item" href="/proizvodi/prikaz/izradeno"><i class="bi bi-gear-fill"></i> Izrađeno do sada</a>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Računi
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @foreach ($workYears as $workYear)
                                        <a class="dropdown-item" href="/racuni/godina/{{ $workYear->year}}"><i class="bi bi-invoice"></i> {{ $workYear->year}}</a>
                                    @endforeach
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Knjiga prometa
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @foreach ($workYears as $workYear)
                                    <a class="dropdown-item" href="/knjiga-prometa/godina/{{ $workYear->year}}"><i class="bi bi-invoice"></i> {{ $workYear->year}}</a>
                                    @endforeach                            
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Etikete
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/dostavne-etikete"><i class="bi bi-printer"></i> Dostavne etikete</a>
                                </div>
                            </li>
                        @endguest

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="p-2 mx-auto" style="margin-top:70px;">
            @yield('content')
        </main>
    </div>

    @include('includes.messages')

</body>
</html>
