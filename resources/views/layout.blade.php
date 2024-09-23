<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>GenialSystem</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @livewireStyles
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Chart.js and Data Labels Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
</head>

<body class="font-tome antialiased bg-[#F5F5F5]">


    <body class="font-sans antialiased bg-[#F5F5F5]">
        <div id="app">
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
