<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('build/assets/app-f2018de5.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <!-- jQuery JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" defer></script>

    <!-- Select2 init -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>

    <!-- Scripts -->
    <script src="{{ asset('build/assets/app-f163c3af.js') }}" defer></script>
    <script src="{{ asset('js/custom.js') }}" defer></script>


</head>
<body>

<!-- Back to top button -->
<button type="button" class="btn btn-danger btn-floating btn-lg" id="btn-back-to-top"><i class="bi bi-arrow-up"></i></button>

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                {{ $appSettings['company_name']}}
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
                                    <a class="dropdown-item" href="/boje-proizvoda"><i class="bi bi-palette"></i> Boje proizvoda</a>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Narudžbe
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/narudzbe/1"><i class="bi bi-list-stars"></i> Popis narudžbi</a>
                                    <a class="dropdown-item" href="/narudzbe/2"><i class="bi bi-car-front-fill"></i> Poslane narudžbe</a>
                                    <a class="dropdown-item" href="/narudzbe/3"><i class="bi bi-exclamation-diamond-fill"></i> Neodrađene narudžbe</a>
                                    <a class="dropdown-item" href="/narudzbe/4"><i class="bi bi-x-octagon"></i> Otkazane narudžbe</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/proizvodi/1"><i class="bi bi-gear-fill"></i> Proizvodi u izradi</a>
                                    <a class="dropdown-item" href="/proizvodi/2"><i class="bi bi-gear-fill"></i> U izradi po boji</a>
                                    <a class="dropdown-item" href="/proizvodi/3"><i class="bi bi-gear-fill"></i> U izradi po proizvodu</a>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Računi
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @foreach ($workYears as $workYear)
                                        <a class="dropdown-item" href="/racuni/godina/{{ $workYear->year}}"><i class="bi bi-receipt"></i> {{ $workYear->year}}</a>
                                    @endforeach
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Knjiga prometa
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @foreach ($workYears as $workYear)
                                    <a class="dropdown-item" href="/knjiga-prometa/godina/{{ $workYear->year}}"><i class="bi bi-receipt"></i> {{ $workYear->year}}</a>
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
