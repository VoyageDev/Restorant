<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('pageTitle', config('app.name'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body
    class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300 text-gray-800 dark:text-white font-sans antialiased"
    x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">

        <x-sidebar />

        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

            <x-header :title="view()->yieldContent('pageTitle', 'Dashboard')" />

            <main class="w-full grow p-6">
                @yield('content')
            </main>

        </div>
    </div>
</body>

</html>
