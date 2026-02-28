@extends('Admin')

@section('pageTitle', 'Pesanan (Kasir)')

@section('content')
    @if (session('success'))
        <div id="alert-success"
            class="fixed right-9 z-50 mb-6 p-4 bg-green-50 border border-green-200 rounded-lg w-auto max-w-md">
            <p class="text-sm font-medium text-green-800">
                ✓ {{ session('success') }}
            </p>
        </div>
    @endif

    @if (session('error'))
        <div id="alert-error" class="fixed right-9 z-50 mb-6 p-4 bg-red-50 border border-red-200 rounded-lg w-auto max-w-md">
            <p class="text-sm font-medium text-red-800">
                ✗ {{ session('error') }}
            </p>
        </div>
    @endif

    <div x-data="orderManager()" class="space-y-6">

        {{-- pilih meja --}}
        <div
            class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm border-t-4 border-t-purple-500">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Pilih Meja Aktif</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pilih meja untuk melihat atau menambah draft
                        pesanan.
                    </p>
                </div>
                <div x-data="{ open: false, search: '', selectedText: '-- Silakan Pilih Meja --' }" class="relative w-full md:w-72">

                    <div @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-3 text-sm font-bold text-purple-700 bg-purple-50 dark:bg-gray-900 border border-purple-200 dark:border-purple-700 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors">
                        <span x-text="selectedText"></span>
                        <svg class="w-4 h-4 text-purple-500 dark:text-purple-400 transform transition-transform duration-200"
                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>

                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-purple-100 dark:border-gray-700 rounded-lg shadow-xl"
                        style="display: none;">

                        <div class="p-2 border-b border-gray-100 dark:border-gray-700">
                            <input type="text" x-model="search" placeholder="Cari no meja atau status..."
                                class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-md focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 placeholder-gray-400 transition-colors">
                        </div>

                        <ul class="overflow-y-auto max-h-60">
                            @forelse($mejas as $meja)
                                <li x-show="search === '' || 'Meja {{ $meja->no_meja }} {{ $meja->status }}'.toLowerCase().includes(search.toLowerCase())"
                                    @click=" selectedTable = '{{ $meja->id }}';
                                    selectedText = 'Meja {{ $meja->no_meja }}';
                                    open = false;
                                    resetForm();"
                                    class="px-4 py-3 cursor-pointer hover:bg-purple-50 dark:hover:bg-gray-700 border-b border-gray-50 dark:border-gray-700 last:border-0 transition-colors">

                                    <div class="text-sm font-bold text-gray-800 dark:text-gray-200">
                                        Meja {{ $meja->no_meja }}
                                    </div>

                                    <div class="text-xs mt-1 text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                        @if ($meja->status === 'Tersedia')
                                            <span class="text-green-600 dark:text-green-400 font-semibold">✓ Tersedia</span>
                                        @elseif ($meja->status === 'Reserved')
                                            <span class="text-yellow-600 dark:text-yellow-400 font-semibold">◆
                                                Reserved</span>
                                        @endif
                                        <span class="ml-1">({{ $meja->jumlah_orang }} orang)</span>
                                    </div>

                                </li>
                            @empty
                                <li class="px-4 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                    Belum ada meja tersedia
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- draft pesanan --}}
        <div x-show="selectedTable !== ''" style="display: none;" class="space-y-6" x-transition>

            <div class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm">
                <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white"
                    x-text="isEditing ? 'Edit Item Pesanan' : 'Tambah Item ke Meja'"></h3>

                <form @submit.prevent="addOrder()">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Waiter
                                (Pelayan)</label>
                            <select x-model="form.waiter_name" required
                                class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">

                                <option value="">Pilih Waiter...</option>

                                @forelse ($waiters as $waiter)
                                    <option value="{{ $waiter->nama_lengkap }}">{{ $waiter->nama_lengkap }}</option>
                                @empty
                                    <option value="" disabled>Belum ada data waiter</option>
                                @endforelse

                            </select>
                        </div>
                        <div x-data="{ open: false, search: '' }" class="relative w-full">

                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Pilih
                                Menu</label>

                            <div @click="open = !open"
                                class="flex items-center justify-between w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">

                                <span x-text="form.menu_name !== '' ? form.menu_name : 'Pilih Menu...'"
                                    class="font-medium text-gray-900 dark:text-white"></span>

                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 transform transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>

                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl"
                                style="display: none;">

                                <div class="p-2 border-b border-gray-100 dark:border-gray-700">
                                    <input type="text" x-model="search" placeholder="Cari nama menu..."
                                        class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-md focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-400 transition-colors">
                                </div>

                                <ul class="overflow-y-auto max-h-60">
                                    @forelse ($menus as $menu)
                                        <li x-show="search === '' || '{{ strtolower($menu->name) }}'.includes(search.toLowerCase())"
                                            @click="
                        form.menu_id = '{{ $menu->id }}';
                        form.menu_name = '{{ $menu->name }}';
                        form.price = {{ $menu->price }};
                        form.daily_stock_remaining = {{ $menu->daily_stock_remaining }};
                        open = false;
                        search = '';
                    "
                                            class="px-4 py-3 cursor-pointer hover:bg-blue-50 dark:hover:bg-gray-700 border-b border-gray-50 dark:border-gray-700 last:border-0 transition-colors {{ $menu->daily_stock_remaining == 0 ? 'opacity-50 cursor-not-allowed' : '' }}">

                                            <div class="text-sm font-bold text-gray-800 dark:text-gray-200">
                                                {{ $menu->name }}
                                            </div>
                                            <div class="text-xs mt-1 flex justify-between items-center">
                                                <span class="text-blue-600 dark:text-blue-400 font-medium">
                                                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                                                </span>
                                                <span
                                                    class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $menu->daily_stock_remaining > 0 ? 'text-green-800 bg-green-100 dark:bg-green-900 dark:text-green-200' : 'text-red-800 bg-red-100 dark:bg-red-900 dark:text-red-200' }}">
                                                    Tersisa: {{ $menu->daily_stock_remaining }}
                                                </span>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="px-4 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                            Belum ada menu tersedia
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Porsi
                                <span class="text-xs font-normal text-gray-500">(Tersisa Hari Ini: <span
                                        x-text="form.daily_stock_remaining > 0 ? form.daily_stock_remaining : '0'"></span>)</span>
                            </label>
                            <input type="number" x-model.number="form.qty" min="1"
                                :max="form.daily_stock_remaining > 0 ? form.daily_stock_remaining : 0" :disabled="form.daily_stock_remaining <= 0"
                                required
                                class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <p x-show="form.daily_stock_remaining <= 0" class="text-xs text-red-600 dark:text-red-400 mt-1">
                                Menu ini sedang habis
                            </p>
                            <p x-show="form.qty > form.daily_stock_remaining && form.daily_stock_remaining > 0"
                                class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                                Jumlah melebihi stock yang tersedia
                            </p>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Tipe
                                Order</label>
                            <select x-model="form.order_type" required
                                class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                <option value="dine_in">Dine-in (Makan di tempat)</option>
                                <option value="take_away">Takeaway (Bungkus)</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Catatan Khusus
                                (Opsional)</label>
                            <input type="text" x-model="form.note" placeholder="Cth: Jangan terlalu pedas..."
                                class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" x-show="isEditing" @click="resetForm()"
                            class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            Batal Edit
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <span x-text="isEditing ? 'Update Item' : '+ Tambah ke Draft'"></span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm">
                <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white">
                    Draft Pesanan: <span class="text-purple-600 dark:text-purple-400"
                        x-text="'Meja ' + selectedTable"></span>
                </h3>

                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg mb-6">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 whitespace-nowrap">
                        <thead
                            class="text-xs text-gray-700 dark:text-white uppercase bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
                            <tr>
                                <th class="px-4 py-3 font-medium">Menu</th>
                                <th class="px-4 py-3 font-medium text-center">Jumlah</th>
                                <th class="px-4 py-3 font-medium text-right">Subtotal</th>
                                <th class="px-4 py-3 font-medium">Waiter & Tipe</th>
                                <th class="px-4 py-3 font-medium">Catatan</th>
                                <th class="px-4 py-3 font-medium text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr x-show="currentDrafts.length === 0" class="bg-white dark:bg-gray-800">
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Belum
                                    ada
                                    pesanan untuk meja ini.</td>
                            </tr>

                            <template x-for="item in currentDrafts" :key="item.id">
                                <tr
                                    class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white" x-text="item.menu_name">
                                    </td>
                                    <td class="px-4 py-3 text-center" x-text="item.qty"></td>
                                    <td class="px-4 py-3 text-right"
                                        x-text="'Rp ' + (item.price * item.qty).toLocaleString('id-ID')"></td>
                                    <td class="px-4 py-3">
                                        <span x-text="item.waiter_name" class="block"></span>
                                        <span
                                            class="px-2 py-0.5 mt-1 text-[10px] font-medium text-blue-800 bg-blue-100 dark:bg-blue-900 dark:text-blue-200 rounded-full inline-block"
                                            x-text="item.order_type === 'dine_in' ? 'Dine-in' : 'Takeaway'"></span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-400 italic" x-text="item.note || '-'"></td>
                                    <td class="px-4 py-3 text-center space-x-1">
                                        <button @click="editOrder(item)"
                                            class="inline-flex items-center px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded hover:bg-yellow-200 dark:bg-gray-700 dark:text-blue-500 dark:hover:bg-gray-600">
                                            Edit
                                        </button>
                                        <button @click="deleteOrder(item.id)"
                                            class="inline-flex items-center px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded hover:bg-red-200 dark:bg-gray-700 dark:text-red-500 dark:hover:bg-gray-600">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div x-show="currentDrafts.length > 0"
                    class="flex flex-col md:flex-row justify-between items-center bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="mb-4 md:mb-0">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Tagihan Sementara:</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white"
                            x-text="'Rp ' + calculateTotal().toLocaleString('id-ID')"></p>
                    </div>

                    <button @click="submitOrder()"
                        class="px-8 py-3 text-sm font-bold text-white transition-colors bg-green-600 rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        Simpan ke Database &check;
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderManager', () => ({
                selectedTable: '',
                drafts: {},
                isEditing: false,

                // Model Form
                form: {
                    id: null,
                    waiter_name: '',
                    menu_id: '',
                    menu_name: '',
                    price: 0,
                    daily_stock_remaining: 0,
                    qty: 1,
                    order_type: 'dine_in',
                    note: ''
                },

                init() {
                    // Load drafts dari localStorage saat halaman pertama kali dimuat
                    const saved = localStorage.getItem('pesananDrafts');
                    if (saved) {
                        this.drafts = JSON.parse(saved);
                    }

                    // Watch untuk save ke localStorage setiap kali drafts berubah
                    this.$watch('drafts', (newVal) => {
                        localStorage.setItem('pesananDrafts', JSON.stringify(newVal));
                    }, {
                        deep: true
                    });
                },

                // Mengambil draft meja yang sedang dipilih
                get currentDrafts() {
                    if (!this.selectedTable) return [];
                    return this.drafts[this.selectedTable] || [];
                },

                // Fungsi mengambil nama dan harga menu saat opsi dropdown diubah
                setMenuDetails(event) {
                    const selectedOption = event.target.options[event.target.selectedIndex];
                    if (selectedOption.value !== "") {
                        this.form.menu_name = selectedOption.getAttribute('data-name');
                        this.form.price = parseInt(selectedOption.getAttribute('data-price'));
                    } else {
                        this.form.menu_name = '';
                        this.form.price = 0;
                    }
                },

                addOrder() {
                    // Validasi: Pastikan menu dipilih
                    if (!this.form.menu_id) {
                        alert('Harap pilih menu terlebih dahulu!');
                        return;
                    }

                    // Validasi: Pastikan qty tidak lebih dari daily_stock_remaining
                    if (this.form.qty > this.form.daily_stock_remaining) {
                        alert('Jumlah pesanan melebihi stock yang tersedia! Stock tersisa: ' + this.form
                            .daily_stock_remaining);
                        return;
                    }

                    // Validasi: Pastikan daily_stock_remaining > 0
                    if (this.form.daily_stock_remaining <= 0) {
                        alert('Menu ini sedang habis!');
                        return;
                    }

                    // Inisialisasi array jika meja belum pernah dipesan
                    if (!this.drafts[this.selectedTable]) {
                        this.drafts[this.selectedTable] = [];
                    }

                    if (this.isEditing) {
                        // Update Item
                        const index = this.drafts[this.selectedTable].findIndex(i => i.id === this.form
                            .id);
                        if (index > -1) {
                            this.drafts[this.selectedTable][index] = {
                                ...this.form
                            };
                        }
                    } else {
                        // Tambah Item Baru (Berikan ID unik sementara berdasarkan timestamp)
                        this.drafts[this.selectedTable].push({
                            ...this.form,
                            id: Date.now()
                        });
                    }

                    this.resetForm();
                },

                editOrder(item) {
                    this.form = {
                        ...item
                    };
                    this.isEditing = true;
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                },

                deleteOrder(id) {
                    if (confirm('Hapus menu ini dari draft?')) {
                        this.drafts[this.selectedTable] = this.drafts[this.selectedTable].filter(i => i
                            .id !== id);
                    }
                },

                calculateTotal() {
                    return this.currentDrafts.reduce((total, item) => total + (item.price * item.qty),
                        0);
                },

                resetForm() {
                    this.form.id = null;
                    this.form.menu_id = '';
                    this.form.menu_name = '';
                    this.form.price = 0;
                    this.form.daily_stock_remaining = 0;
                    this.form.qty = 1;
                    this.form.order_type = 'dine_in';
                    this.form.note = '';
                    this.isEditing = false;
                },

                submitOrder() {
                    // Validasi: Pastikan meja dipilih
                    if (!this.selectedTable) {
                        alert('Harap pilih meja terlebih dahulu!');
                        return;
                    }

                    // Validasi: Pastikan ada items di draft
                    if (this.currentDrafts.length === 0) {
                        alert('Pesanan kosong! Harap tambahkan menu terlebih dahulu.');
                        return;
                    }

                    // Submit via AJAX
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('meja_id', this.selectedTable);
                    formData.append('items', JSON.stringify(this.currentDrafts));

                    // Log request details
                    console.log('Submitting order:', {
                        meja_id: this.selectedTable,
                        items: this.currentDrafts,
                        total: this.calculateTotal()
                    });

                    fetch('{{ route('pesanan.store') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            // Log response details
                            console.log('Response status:', response.status);
                            console.log('Response headers:', {
                                'content-type': response.headers.get('content-type'),
                                'content-length': response.headers.get('content-length')
                            });

                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                return response.json().then(data => ({
                                    ok: response.ok,
                                    status: response.status,
                                    data
                                }));
                            }

                            return response.text().then(text => ({
                                ok: response.ok,
                                status: response.status,
                                text
                            }));
                        })
                        .then(result => {
                            if (result.data) {
                                console.log('Response data:', result.data);
                            } else {
                                console.error('Non-JSON Response received:');
                                console.error('First 500 chars:', (result.text || '').substring(0,
                                    500));
                            }

                            if (!result.ok) {
                                const message = result.data && result.data.message ? result.data
                                    .message :
                                    'HTTP Error: ' + result.status;
                                throw new Error(message);
                            }

                            if (result.data && result.data.success) {
                                // Hapus draft hanya untuk meja yang baru di-submit
                                delete this.drafts[this.selectedTable];
                                localStorage.setItem('pesananDrafts', JSON.stringify(this.drafts));

                                // Reset form dan meja
                                this.selectedTable = '';
                                this.resetForm();

                                // Tampilkan pesan sukses
                                alert('✓ Pesanan berhasil disimpan ke database!');

                                // Reload page untuk update data
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                alert('Error: ' + ((result.data && result.data.message) ||
                                    'Terjadi kesalahan'));
                            }
                        })
                        .catch(error => {
                            console.error('Full Error Details:', error);
                            console.error('Error Stack:', error.stack);
                            alert('❌ Error: ' + error.message);
                        });

                    return false; // Prevent default form submission
                }
            }));
        });
        
        // Auto-hide alerts after 3 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('#alert-success, #alert-error');
            alerts.forEach(alert => {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);
    </script>
@endsection
