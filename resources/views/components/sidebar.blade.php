<div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
    class="fixed inset-0 z-20 bg-black/50 lg:hidden" style="display: none;">
</div>

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="absolute inset-y-0 left-0 z-30 flex flex-col w-64 px-4 py-6 bg-white dark:bg-gray-800  border-r shadow-sm transition-transform duration-300 ease-in-out lg:static lg:translate-x-0">

    <div class="flex items-center justify-between pb-6 border-b lg:justify-center">
        <h2 class="text-2xl font-bold text-blue-600">Restorant</h2>

        <button @click="sidebarOpen = false" class="text-gray-500 lg:hidden hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="flex flex-col mt-6 space-y-2">
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'text-blue-600  bg-blue-50 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('pesanan') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('pesanan') ? 'text-blue-600 bg-blue-50 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-file-invoice">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path
                    d="M12 2l.117 .007a1 1 0 0 1 .876 .876l.007 .117v4l.005 .15a2 2 0 0 0 1.838 1.844l.157 .006h4l.117 .007a1 1 0 0 1 .876 .876l.007 .117v9a3 3 0 0 1 -2.824 2.995l-.176 .005h-10a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-14a3 3 0 0 1 2.824 -2.995l.176 -.005zm4 15h-2a1 1 0 0 0 0 2h2a1 1 0 0 0 0 -2m0 -4h-8a1 1 0 0 0 0 2h8a1 1 0 0 0 0 -2m-7 -7h-1a1 1 0 1 0 0 2h1a1 1 0 1 0 0 -2" />
                <path d="M19 7h-4l-.001 -4.001z" />
            </svg>
            Pesanan
        </a>
        <a href="{{ route('pembayaran') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('pembayaran') ? 'text-blue-600 bg-blue-50 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-credit-card-pay">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12 19h-6a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v4.5" />
                <path d="M3 10h18" />
                <path d="M16 19h6" />
                <path d="M19 16l3 3l-3 3" />
                <path d="M7.005 15h.005" />
                <path d="M11 15h2" />
            </svg>
            Pembayaran
        </a>
        <a href="{{ route('stock-menu') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('stock-menu') ? 'text-blue-600 bg-blue-50 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-package-icon lucide-package">
                <path
                    d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" />
                <path d="M12 22V12" />
                <polyline points="3.29 7 12 12 20.71 7" />
                <path d="m7.5 4.27 9 5.15" />
            </svg>
            Stock Menu
        </a>
        <a href="{{ route('meja') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('meja') ? 'text-blue-600 bg-blue-50 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-desk">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M3 6h18" />
                <path d="M4 6v13" />
                <path d="M20 19v-13" />
                <path d="M4 10h16" />
                <path d="M15 6v8a2 2 0 0 0 2 2h3" />
            </svg>
            Manajemen Meja
        </a>
        <a href="{{ route('kategori') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('kategori') ? 'text-blue-600 bg-blue-50 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-category">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M4 4h6v6h-6l0 -6" />
                <path d="M14 4h6v6h-6l0 -6" />
                <path d="M4 14h6v6h-6l0 -6" />
                <path d="M14 17a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
            </svg>
            Manajemen Kategori
        </a>
        <a href="{{ route('manajemen-user') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('manajemen-user') ? 'text-blue-600 bg-blue-50 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
            </svg>
            Manajemen User
        </a>
        <a href="{{ route('karyawan') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('karyawan') ? 'text-blue-600 bg-blue-50 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="lucide lucide-id-card-lanyard-icon lucide-id-card-lanyard">
                <path d="M13.5 8h-3" />
                <path d="m15 2-1 2h3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h3" />
                <path d="M16.899 22A5 5 0 0 0 7.1 22" />
                <path d="m9 2 3 6" />
                <circle cx="12" cy="15" r="3" />
            </svg>
            Karyawan
        </a>
        <a href="{{ route('pendapatan') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('pendapatan') ? 'text-blue-600 bg-blue-50 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="lucide lucide-banknote-arrow-up-icon lucide-banknote-arrow-up">
                <path d="M12 18H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5" />
                <path d="M18 12h.01" />
                <path d="M19 22v-6" />
                <path d="m22 19-3-3-3 3" />
                <path d="M6 12h.01" />
                <circle cx="12" cy="12" r="2" />
            </svg>
            Pendapatan
        </a>
        <a href="{{ route('history') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('history') ? 'text-blue-600 bg-blue-50 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="lucide lucide-history-icon lucide-history">
                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                <path d="M3 3v5h5" />
                <path d="M12 7v5l4 2" />
            </svg>
            History
        </a>
    </nav>
</aside>
