<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>GenialSystem</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

</head>

<body class="font-tome antialiased bg-[#F5F5F5]">
    <div id="app">
        <div class="fixed inset-0 bg-[#E8E8E8] z-50 flex items-center justify-center lg:hidden">
            <div class="text-[#222222] text-center p-4">
                <h2 class="text-4xl font-semibold">La piattaforma è visualizzabile solo in versione desktop.</h2>
            </div>
        </div>

        <!-- Sidebar -->
        @livewire('sidebar')
        <!-- Main content -->
        <div class="ml-64">
            <!-- Navbar -->
            @livewire('navbar')

            <!-- Main Content -->
            <main class="mx-6 my-2">
                @livewire('result-banner')

                @yield('content')
            </main>
        </div>
    </div>
    @livewireScripts
    @livewire('wire-elements-modal')
</body>


</html>
