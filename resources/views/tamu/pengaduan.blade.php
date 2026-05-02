<x-layouts::app.landing :title="__('Form Pengaduan Sampah')">
    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto font-sans">

        {{-- Header Page --}}
        <div class="text-center mb-6">
            <div class="flex flex-col items-center justify-center text-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo DLH" class="h-32 w-auto object-contain mb-2">
                <h1 class="text-xl font-extrabold tracking-tight text-zinc-900 dark:text-white">
                    Dinas Lingkungan Hidup
                </h1>
            </div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">Form Laporan Kebersihan</h1>
            <p class="mt-3 text-zinc-600 dark:text-zinc-400 max-w-xl mx-auto">
                Bantu kami menjaga kebersihan lingkungan. Lengkapi form di bawah ini untuk melaporkan tumpukan sampah
                atau masalah lingkungan di sekitar Anda.
            </p>
        </div>

        <flux:card class="p-6 sm:p-10 shadow-lg border-emerald-100 dark:border-zinc-800">

            {{-- PENTING: enctype="multipart/form-data" wajib ada untuk upload file/foto --}}
            <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10"
                onsubmit="return validateLokasi()">
                @csrf

                {{-- SECTION 1: Informasi Pelapor --}}
                <fieldset>
                    <legend
                        class="text-lg font-bold text-zinc-900 dark:text-white mb-4 border-b border-zinc-200 dark:border-zinc-700 w-full pb-2">
                        1. Informasi Pelapor
                    </legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:input name="nama_pelapor" label="Nama Lengkap" placeholder="Masukkan nama Anda..."
                            required />

                        {{-- Opsional tapi sangat direkomendasikan untuk fitur pengaduan publik --}}
                        <flux:input name="kontak" label="No. WhatsApp / Email (Opsional)"
                            placeholder="Agar petugas bisa menghubungi Anda..." />
                    </div>
                </fieldset>

                {{-- SECTION 2: Detail Kejadian & Foto --}}
                <fieldset>
                    <legend
                        class="text-lg font-bold text-zinc-900 dark:text-white mb-4 border-b border-zinc-200 dark:border-zinc-700 w-full pb-2">
                        2. Detail Laporan
                    </legend>
                    <div class="space-y-6">
                        <flux:select id="tipe_pegawai" name="tipe_sampah" label="Tipe Pegawai" required>
                            <option value="">Pilih Tipe Sampah...</option>
                            <option value="organik" {{ old('tipe_sampah') == 'organik' ? 'selected' : '' }}>Organik
                            </option>
                            <option value="non_organik" {{ old('tipe_sampah') == 'non_organik' ? 'selected' : '' }}>Non
                                Organik</option>
                        </flux:select>
                        <flux:textarea name="deskripsi" label="Deskripsi Kondisi" rows="4"
                            placeholder="Contoh: Terdapat tumpukan sampah rumah tangga yang menyumbat selokan di depan minimarket, baunya sangat menyengat..."
                            required></flux:textarea>

                        <div
                            class="bg-zinc-50 dark:bg-zinc-900/50 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700">
                            <flux:input type="file" name="foto_bukti" label="Foto Bukti Kejadian" accept="image/*"
                                required />
                            <p class="text-xs text-zinc-500 mt-2 flex items-center gap-1">
                                <flux:icon.information-circle class="w-4 h-4" />
                                Format yang didukung: JPG, PNG. Maksimal ukuran 5MB. Pastikan foto terlihat jelas.
                            </p>
                        </div>
                    </div>
                </fieldset>

                {{-- SECTION 3: Lokasi --}}
                <fieldset>
                    <legend
                        class="text-lg font-bold text-zinc-900 dark:text-white mb-4 border-b border-zinc-200 dark:border-zinc-700 w-full pb-2">
                        3. Lokasi Kejadian
                    </legend>
                    <div class="space-y-6">
                        <flux:textarea name="lokasi_manual" label="Alamat / Patokan Manual (opsional)" rows="2"
                            placeholder="Contoh: Jl. Panglima Sudirman, tepat di bawah tiang listrik dekat pertigaan.">
                        </flux:textarea>

                        <div
                            class="border rounded-xl p-5 bg-zinc-50 dark:bg-zinc-900/50 border-zinc-200 dark:border-zinc-700">
                            <div
                                class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
                                <div>
                                    <h4 class="font-bold text-zinc-900 dark:text-white text-sm">Peta Digital
                                    </h4>
                                    <p class="text-xs text-zinc-500 mt-1">Tandai titik koordinat agar truk sampah lebih
                                        mudah mencari lokasi.</p>
                                </div>

                                {{-- Tambahkan id="btn-get-location" dan type="button" --}}
                                <flux:button id="btn-get-location" type="button" size="sm" variant="outline"
                                    icon="map-pin">
                                    Gunakan Lokasi Saat Ini
                                </flux:button>
                            </div>

                            {{-- Container Peta Leaflet (Z-index 10 agar control map tidak menimpa header) --}}
                            <div id="map" class="w-full h-64 rounded-lg border border-zinc-300 dark:border-zinc-600"
                                style="z-index: 10;"></div>

                            {{-- Input tersembunyi untuk dikirim ke Controller --}}
                            <input type="hidden" name="latitude" id="latitude" required>
                            <input type="hidden" name="longitude" id="longitude" required>

                            @error('latitude')
                                <p class="text-sm font-medium text-red-500 mt-2 flex items-center gap-1">
                                    <flux:icon.exclamation-circle class="w-4 h-4" />
                                    {{ $message }}
                                </p>
                            @enderror

                            <p id="location-status" class="text-xs font-medium text-emerald-600 mt-2 hidden">
                                ✓ Koordinat berhasil ditandai.
                            </p>
                            <p id="error-map"
                                class="text-sm font-medium text-red-500 mt-2 hidden flex items-center gap-1">
                                <flux:icon.exclamation-circle class="w-4 h-4" />
                                Anda wajib menandai lokasi kejadian pada peta atau gunakan lokasi saat ini!
                            </p>
                        </div>
                    </div>
                </fieldset>

                {{-- Footer / Tombol Aksi --}}
                <div
                    class="pt-6 border-t border-zinc-200 dark:border-zinc-700 flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <flux:button href="{{ route('home') }}" variant="subtle" class="w-full sm:w-auto" wire:navigate>
                        Kembali</flux:button>

                    <flux:button type="submit" variant="primary"
                        class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white cursor-pointer">
                        Kirim Laporan Sekarang
                    </flux:button>
                </div>

            </form>
        </flux:card>
    </div>
    <script>
        function validateLokasi() {
            const lat = document.getElementById('latitude').value;
            const lng = document.getElementById('longitude').value;
            const errorText = document.getElementById('error-map');
            const mapDiv = document.getElementById('map');

            // Jika user belum klik peta (koordinat kosong)
            if (!lat || !lng) {
                // Munculkan teks error
                if (errorText) errorText.classList.remove('hidden');

                // Beri border merah menyala pada peta
                if (mapDiv) {
                    mapDiv.classList.add('border-red-500', 'border-2');
                    mapDiv.classList.remove('border-zinc-300', 'dark:border-zinc-600');

                    // Arahkan layar otomatis ke arah peta
                    mapDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }

                // TAHAN form agar tidak terkirim
                return false;
            }

            // IZINKAN form terkirim jika koordinat sudah ada
            return true;
        }
        // Gunakan fungsi terpisah agar bisa dipanggil ulang jika navigasi via Livewire
        function initLeafletMap() {
            // Cek apakah elemen map ada di halaman ini
            const mapContainer = document.getElementById('map');
            if (!mapContainer) return;

            // Mencegah error "Map container is already initialized" jika berpindah halaman
            if (L.DomUtil.hasClass(mapContainer, 'leaflet-container')) {
                return;
            }

            // Titik awal: Surabaya / Gresik (Bisa Anda ubah sesuai kebutuhan)
            const defaultLat = -7.0477686;
            const defaultLng = 112.7324695;

            // Inisialisasi Peta
            const map = L.map('map', {
                center: [defaultLat, defaultLng],  // Titik tengah peta
                zoom: 16,                          // Jarak zoom
                scrollWheelZoom: false,            // Mencegah peta ter-zoom saat scroll mouse
                attributionControl: false          // Menghilangkan teks "Leaflet" di pojok bawah
            });

            // Gunakan Tile dari OpenStreetMap (Gratis)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            let marker; // Variabel untuk menyimpan pin/marker

            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const statusText = document.getElementById('location-status');
            const errorText = document.getElementById('error-map');

            // Fungsi untuk menaruh/memindahkan marker
            function updateMarker(lat, lng) {
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }

                // Isi hidden input untuk disubmit ke database
                latInput.value = lat;
                lngInput.value = lng;

                // Munculkan teks sukses
                statusText.classList.remove('hidden');
                if (errorText) {
                    errorText.classList.add('hidden');
                }
                if (mapContainer) {
                    mapContainer.classList.remove('border-red-500', 'border-2');
                    mapContainer.classList.add('border-zinc-300', 'dark:border-zinc-600');
                }
            }

            // Fitur 1: Pilih lokasi dengan mengklik Peta
            map.on('click', function (e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                updateMarker(lat, lng);
            });

            // Fitur 2: Ambil Lokasi GPS Perangkat Saat Ini
            const btnGetLocation = document.getElementById('btn-get-location');
            if (btnGetLocation) {
                btnGetLocation.addEventListener('click', function () {
                    // Pengecekan apakah browser mendukung Geolocation
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            // Jika Berhasil diizinkan user
                            function (position) {
                                const lat = position.coords.latitude;
                                const lng = position.coords.longitude;

                                updateMarker(lat, lng);

                                // Zoom otomatis ke lokasi user
                                map.setView([lat, lng], 17);
                            },
                            // Jika Gagal / Ditolak user
                            function (error) {
                                alert('Gagal mendapatkan lokasi. Pastikan GPS aktif dan Anda mengizinkan akses lokasi pada browser.');
                            },
                            { enableHighAccuracy: true } // Minta akurasi tinggi
                        );
                    } else {
                        alert('Browser Anda tidak mendukung pelacakan lokasi.');
                    }
                });
            }
        }

        // Jalankan saat halaman di-load biasa
        document.addEventListener('DOMContentLoaded', initLeafletMap);

        // Jalankan saat halaman di-load via wire:navigate (Livewire SPA)
        document.addEventListener('livewire:navigated', initLeafletMap);
    </script>
</x-layouts::app.landing>