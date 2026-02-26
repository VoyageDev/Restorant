<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Restoran</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="flex items-center justify-center min-h-screen bg-gray-300 dark:bg-gray-900 transition-colors duration-300 font-sans antialiased">

    <div
        class="w-full max-w-md p-8 space-y-8 bg-gray-200 rounded-2xl shadow-xl dark:bg-gray-800 border border-gray-100 dark:border-gray-700">

        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                Admin Restoran
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Silakan masuk ke akun Anda
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('login.process') }}" method="POST">
            @csrf
            @if ($errors->any())
                <div class="p-3 text-sm text-red-700 bg-red-100 border border-red-200 rounded-lg">
                    {{ $errors->first() }}
                </div>
            @endif
            <div class="space-y-5">
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               transition-colors"
                        placeholder="kasir@restoran.com">
                </div>

                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Password
                    </label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="w-full px-4 py-3 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               transition-colors"
                        placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember" type="checkbox"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded
                               focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800
                               dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                    <label for="remember-me" class="block ml-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                        Ingat Saya
                    </label>
                </div>
            </div>

            <div>
                <button type="submit"
                    class="flex justify-center w-full px-4 py-3 text-sm font-bold text-white transition-colors
                           bg-blue-600 border border-transparent rounded-lg shadow-sm
                           hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                           dark:focus:ring-offset-gray-800">
                    Masuk
                </button>
            </div>

        </form>
    </div>

</body>

</html>
