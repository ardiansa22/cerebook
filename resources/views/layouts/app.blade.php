<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.head')
</head>
<body class="bg-gray-50">
    @include('layouts.nav')
    
    <main>
        @include('layouts.search')
        
        {{ $slot }}

    </main>
    @include('layouts.script')
</body>
</html>