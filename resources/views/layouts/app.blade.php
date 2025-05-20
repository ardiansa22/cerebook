<!DOCTYPE html>
<html lang="id">
<head>
    @include('layouts.head')
</head>
<body>

    @include('layouts.nav')


    <main>
        {{$slot}}
    </main>

    @include('layouts.script')

</body>
</html>
