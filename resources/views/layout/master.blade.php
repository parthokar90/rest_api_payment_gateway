<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


@include('layout.header')

<body class="antialiased">
    <div class="text-center">
        @auth
            Welcome: {{ auth()->user()->name }}
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @else
            <!-- Show login/register links or any other content for non-authenticated users -->
        @endauth
    </div>
    @yield('content')
</body>

@include('layout.footer')

</html>
