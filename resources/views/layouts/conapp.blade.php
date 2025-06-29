<!DOCTYPE html>
<html lang="id">
<head>
    @include('layouts.head')
    @livewireStyles
</head>
<body>
    @include('layouts.nav')

    <main>
        @yield('content')
    </main>

    @include('layouts.script')
    @livewireScripts
</body>
</html>
