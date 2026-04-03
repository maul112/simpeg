<x-layouts::app.landing :title="__('Selamat Datang')">
    <div class="bg-white text-zinc-900 font-sans">

        <nav class="flex items-center justify-between px-8 py-6 max-w-7xl mx-auto">
            <div class="flex items-center gap-2">
                <flux:icon.shield-check class="text-emerald-600 w-8 h-8" />
                <span class="text-2xl font-bold tracking-tight">SIM<span class="text-emerald-600">PEG</span></span>
            </div>
            <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="{{ route('home') }}" class="hover:text-emerald-600 transition-colors">Beranda</a>
                <a href="#fitur" class="hover:text-emerald-600 transition-colors">Fitur</a>
                <a href="#" class="hover:text-emerald-600 transition-colors">Tentang Kami</a>
                {{-- <flux:button href="{{ route('login') }}" variant="primary"
                    class="bg-emerald-600 hover:bg-emerald-700">
                    Masuk Ke Sistem
                </flux:button> --}}
                @auth
                    <a href="{{ url(auth()->check() && auth()->user()->employee->role == 'admin' ? '/dashboard' : '/homepage') }}"
                        class="block bg-emerald-600 hover:bg-emerald-700">
                        Dashboard
                    </a>
                @else
                    <div class="flex gap-4">
                        <flux:button href="{{ route('login') }}"
                            class="block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-emerald-600 hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                            wire:navigate>
                            Log in
                        </flux:button>

                        @if (Route::has('register'))
                                <flux:button href="{{ route('register') }}" variant="primary"
                                    class="block bg-emerald-600 hover:bg-emerald-700" wire:navigate>
                                    Tamu
                                </flux:button>
                            </div>
                        @endif
                @endauth
            </div>
        </nav>

        <section class="relative overflow-hidden bg-emerald-50/50 py-20 px-8">
            <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <flux:badge color="green" size="sm">Sistem Informasi Pegawai v2.0</flux:badge>
                    <h1 class="text-5xl md:text-6xl font-extrabold leading-tight">
                        Manajemen Pegawai <br>
                        <span class="text-emerald-600">Lebih Modern & Efisien.</span>
                    </h1>
                    <p class="text-lg text-zinc-600 max-w-lg">
                        Optimalkan produktivitas instansi Anda dengan pengelolaan data pegawai yang transparan,
                        otomatis, dan terintegrasi dalam satu platform.
                    </p>
                    <div class="flex gap-4">
                        <flux:button variant="primary" class="bg-emerald-600 px-8">Mulai Sekarang</flux:button>
                        <flux:button variant="ghost">Pelajari Fitur</flux:button>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -inset-4 bg-emerald-200/50 rounded-full blur-3xl"></div>
                    {{-- <img src="https://fluxui.dev/img/demo/user.png" alt="Dashboard Preview" --}} {{--
                        class="relative rounded-2xl shadow-2xl border w-full border-white/20"> --}}
                    {{-- <div
                        class="flex items-center justify-center w-full h-16 bg-emerald-100 rounded-2xl shadow-sm border border-emerald-200">
                        <flux:icon.shield-check class="block m-auto text-emerald-600 bg-emerald-300 w-8 h-8" />
                    </div> --}}
                    <div
                        class="relative overflow-hidden aspect-video w-full bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-emerald-100 dark:border-emerald-900/30 flex flex-col">

                        <div
                            class="flex items-center gap-1.5 px-4 py-3 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-white/5">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-400"></div>
                        </div>

                        <div class="flex-1 flex items-center justify-center bg-emerald-50/30 dark:bg-emerald-950/10">
                            <div class="text-center space-y-3">
                                <flux:icon.shield-check class="mx-auto text-emerald-600 w-12 h-12 opacity-80" />
                                <p
                                    class="text-emerald-700/50 dark:text-emerald-400/50 font-medium text-sm tracking-widest uppercase">
                                    Preview SIMPEG Dashboard
                                </p>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </section>

        <section id="fitur" class="py-24 px-8 max-w-7xl mx-auto">
            <div class="text-center mb-16 space-y-4">
                <h2 class="text-3xl font-bold">Fitur Unggulan SIMPEG</h2>
                <p class="text-zinc-500 max-w-2xl mx-auto">Dirancang khusus untuk memenuhi kebutuhan administrasi
                    kepegawaian modern di Indonesia.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-8 rounded-2xl border border-zinc-200 hover:border-emerald-500 transition-all group">
                    <div
                        class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-6 group-hover:bg-emerald-600 transition-colors">
                        <flux:icon.user class="text-emerald-600 group-hover:text-white" />
                    </div>
                    <h3 class="text-xl font-bold mb-3">Profil Pegawai Lengkap</h3>
                    <p class="text-zinc-500 text-sm leading-relaxed">Kelola biodata, riwayat pendidikan, hingga keluarga
                        pegawai secara digital dan terpusat.</p>
                </div>

                <div class="p-8 rounded-2xl border border-zinc-200 hover:border-emerald-500 transition-all group">
                    <div
                        class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-6 group-hover:bg-emerald-600 transition-colors">
                        <flux:icon.chart-bar class="text-emerald-600 group-hover:text-white" />
                    </div>
                    <h3 class="text-xl font-bold mb-3">Kenaikan Pangkat Otomatis</h3>
                    <p class="text-zinc-500 text-sm leading-relaxed">Sistem cerdas yang memberikan notifikasi otomatis
                        saat pegawai memasuki periode kenaikan pangkat berkala.</p>
                </div>

                <div class="p-8 rounded-2xl border border-zinc-200 hover:border-emerald-500 transition-all group">
                    <div
                        class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-6 group-hover:bg-emerald-600 transition-colors">
                        <flux:icon.lock-closed class="text-emerald-600 group-hover:text-white" />
                    </div>
                    <h3 class="text-xl font-bold mb-3">Keamanan Multi-Lapis</h3>
                    <p class="text-zinc-500 text-sm leading-relaxed">Dilindungi dengan Autentikasi 2 Faktor (2FA) untuk
                        memastikan data sensitif pegawai tetap aman.</p>
                </div>
            </div>
        </section>

        <section class="px-8 mb-20">
            <div class="max-w-7xl mx-auto bg-emerald-600 rounded-3xl p-12 text-center text-white space-y-6">
                <h2 class="text-4xl font-bold">Siap Tingkatkan Kinerja Instansi?</h2>
                <p class="text-emerald-100 max-w-xl mx-auto">Bergabunglah dengan ribuan instansi yang telah
                    mendigitalisasi manajemen pegawainya bersama kami.</p>
                <flux:button class="bg-white text-emerald-600 hover:bg-emerald-50 border-none px-10">Hubungi Kami
                </flux:button>
            </div>
        </section>

        <footer class="bg-zinc-950 text-zinc-400 py-16 px-8">
            <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-12">
                <div class="col-span-2 space-y-6">
                    <div class="flex items-center gap-2 text-white">
                        <flux:icon.shield-check class="w-8 h-8 text-emerald-500" />
                        <span class="text-2xl font-bold">SIMPEG</span>
                    </div>
                    <p class="max-w-sm">Solusi terbaik untuk transformasi digital manajemen sumber daya manusia di
                        lingkungan pemerintahan dan swasta.</p>
                </div>
                <div class="space-y-4">
                    <h4 class="text-white font-bold">Tautan Cepat</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-emerald-500">Bantuan</a></li>
                        <li><a href="#" class="hover:text-emerald-500">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-emerald-500">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                <div class="space-y-4">
                    <h4 class="text-white font-bold">Kontak</h4>
                    <ul class="space-y-2 text-sm">
                        <li>support@simpeg.go.id</li>
                        <li>(021) 1234-5678</li>
                        <li>Gresik, Jawa Timur, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="max-w-7xl mx-auto border-t border-zinc-800 mt-12 pt-8 text-center text-xs">
                <p>&copy; 2026 SIMPEG Indonesia. Hak Cipta Dilindungi.</p>
            </div>
        </footer>

    </div>
    </x-layouts::app.header_pegawai>