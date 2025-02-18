<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title translate="no">GenialSystem</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">

        <script type="text/javascript" src="./js/googtranslate.js"></script>
        <link rel="stylesheet" href="./css/googtranslate.css">
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <!-- 3. Optional: Add some basic styling -->

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

</head>

@php
    $isCollapsed = session('sidebar.collapsed', false);
@endphp

<body class="font-tome antialiased bg-[#F5F5F5]">
    <div id="app">
        <div id="mobile-warning" class="hidden fixed inset-0 bg-[#E8E8E8] z-50 items-center justify-center">
            <div class="text-[#222222] w-96 p-10">
                <h2 class="text-4xl font-semibold">La piattaforma è visualizzabile solo in versione desktop.</h2>
            </div>
        </div>
        <!-- Sidebar -->
        @livewire('sidebar', ['isCollapsed' => $isCollapsed])
        <!-- Main content -->
        <div id="main-content" class="transition-margin duration-200 {{ $isCollapsed ? 'ml-20' : 'ml-64' }}">
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

    <script>
        function checkViewport() {
            const mobileWarning = document.getElementById('mobile-warning');
            if (window.innerWidth < 1024) {
                mobileWarning.classList.remove('hidden');
            } else {
                mobileWarning.classList.add('hidden');
            }
        }
        window.addEventListener('load', checkViewport);
        window.addEventListener('resize', checkViewport);

        Livewire.on('toggleSidebar', function(event) {
            if (event.collapsed) {
                document.getElementById('main-content').classList.remove('ml-64');
                document.getElementById('main-content').classList.add('ml-20');
            } else {
                document.getElementById('main-content').classList.add('ml-64');
                document.getElementById('main-content').classList.remove('ml-20');
            }
        });
    </script>
</body>

</html>
