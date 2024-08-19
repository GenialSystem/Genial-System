<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @livewireStyles
    @vite('resources/css/app.css')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-[#F5F5F5]">
    <div id="app">
        <!-- Sidebar -->
        @livewire('sidebar')

        <!-- Main content -->
        <div class="ml-64">
            <!-- Navbar -->
            @livewire('navbar')

            <!-- Main Content -->
            <main class="m-4">
                @yield('content')
            </main>
        </div>
    </div>
    @livewireScripts
</body>

</html>