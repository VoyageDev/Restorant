<header
    class="sticky top-0 z-10 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <div class="flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8">

        <div class="flex items-center">
            <button @click="sidebarOpen = true"
                class="p-1 mr-4 text-gray-500 rounded-md lg:hidden hover:bg-gray-100 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
            <h1 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $title ?? 'Dashboard' }}</h1>
        </div>

        <div class="flex items-center space-x-4">
            <button class="flex items-center focus:outline-none">
                <button class="bg-red-500 rounded-2xl px-4 py-2 text-white font-bold hover:bg-red-600">Logout</button>
                <span class="hidden mr-3 text-sm font-medium text-gray-600 md:block"></span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-user">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 2a5 5 0 1 1 -5 5l.005 -.217a5 5 0 0 1 4.995 -4.783z" />
                    <path d="M14 14a5 5 0 0 1 5 5v1a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-1a5 5 0 0 1 5 -5h4z" />
                </svg>
            </button>
        </div>

    </div>
</header>
