<!DOCTYPE html>
<html lang="id">
<head>
    @livewireStyles

    @include('layouts.head')
</head>
<body >

    @include('layouts.nav')


    <main>
        {{$slot}}
    </main>

    @include('layouts.script')
    @livewireScripts

</body>
</html>
