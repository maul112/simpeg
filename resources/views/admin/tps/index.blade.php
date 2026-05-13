<x-layouts::app :title="__('Manajemen Titik TPS')">
    {{-- 1. CSS ASSETS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.css" />
    
    <style>
        #map-picker { 
            height: 400px !important; 
            width: 100% !important; 
            border-radius: 12px;
            border: 2px solid #e4e4e7;
            z-index: 10;
        }
        /* Memastikan kolom pencarian leaflet muncul di atas modal */
        .leaflet-control-geosearch { z-index: 1000 !important; }
        .leaflet-container { cursor: crosshair !important; }
    </style>

    <div class="p-8 max-w-7xl mx-auto space-y-8">
        {{-- Pesan Notifikasi --}}
        @if (session('success'))
            <flux:card class="bg-emerald-50 border-emerald-200 p-4 mb-4">
                <div class="flex items-center gap-3 text-emerald-700">
                    <flux:icon.check-circle class="w-5 h-5" />
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </flux:card>
        @endif

        <flux:card class="overflow-hidden border-none shadow-sm ring-1 ring-zinc-200 dark:ring-zinc-800">
            {{-- Header Section --}}
            <div class="flex flex-col md:flex-row justify-between items-end gap-4 p-6">
                <div>
                    <flux:heading size="xl" class="font-bold tracking-tight">Data Titik TPS</flux:heading>
                    <flux:subheading class="mt-1 text-zinc-500">Kelola lokasi tempat pembuangan sementara dan jadwal pengangkutan.</flux:subheading>
                </div>
                {{-- Tombol Tambah --}}
                <flux:modal.trigger name="create-tps">
                    <flux:button variant="primary" icon="plus" class="bg-emerald-600" onclick="startMapProcess()">Tambah TPS</flux:button>
                </flux:modal.trigger>
            </div>

            {{-- Filter Bar --}}
            <div class="p-4 border-t border-zinc-100 bg-zinc-50/50">
                <form action="{{ route('admin.tps.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-75">
                        <flux:input name="search" icon="magnifying-glass" value="{{ request('search') }}"
                            placeholder="Cari nama TPS atau alamat..." class="border-zinc-200" />
                    </div>

                    <div class="flex items-center gap-2">
                        <flux:select name="kecamatan" onchange="this.form.submit()" class="min-w-45">
                            <option value="">Semua Kecamatan</option>
                            <option value="socah" {{ request('kecamatan') == 'socah' ? 'selected' : '' }}>Socah</option>
                            <option value="bangkalan" {{ request('kecamatan') == 'bangkalan' ? 'selected' : '' }}>Bangkalan</option>
                            <option value="kamal" {{ request('kecamatan') == 'kamal' ? 'selected' : '' }}>Kamal</option>
                        </flux:select>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="pl-6 uppercase text-[11px] font-bold">Nama TPS & Lokasi</flux:table.column>
                    <flux:table.column class="uppercase text-[11px] font-bold">Kecamatan</flux:table.column>
                    <flux:table.column class="uppercase text-[11px] font-bold">Jadwal Angkut</flux:table.column>
                    <flux:table.column class="uppercase text-[11px] font-bold text-center">Peta</flux:table.column>
                    <flux:table.column class="pr-6 text-right uppercase text-[11px] font-bold">Opsi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($tps_data as $tps)
                        <flux:table.row>
                            <flux:table.cell class="pl-6">
                                <div>
                                    <div class="font-semibold leading-tight">{{ $tps->nama_tps }}</div>
                                    <div class="text-[10px] text-zinc-500 mt-1 italic">{{ $tps->alamat }}</div>
                                </div>
                            </flux:table.cell>

                            <flux:table.cell>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-zinc-100 text-zinc-600">
                                    {{ $tps->kecamatan }}
                                </span>
                            </flux:table.cell>

                            <flux:table.cell>
                                <div class="flex items-center gap-1.5 text-xs font-medium text-emerald-600">
                                    <flux:icon.clock class="w-3.5 h-3.5" />
                                    {{ $tps->jadwal }}
                                </div>
                            </flux:table.cell>

                            <flux:table.cell class="text-center">
                                <a href="http://www.google.com/maps/place/{{ $tps->lat }},{{ $tps->lng }}" target="_blank" class="text-blue-500 hover:scale-110 transition-transform inline-block">
                                    <flux:icon.map class="w-5 h-5" />
                                </a>
                            </flux:table.cell>
                            <flux:table.cell align="right" class="pr-6">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- TOMBOL EDIT (TAMBAHKAN INI) --}}
                                    <flux:button 
                                        as="a" 
                                        href="{{ route('admin.tps.edit', $tps->id) }}" 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="pencil" 
                                        class="text-zinc-400 hover:text-blue-600" 
                                    />

                                    {{-- TOMBOL HAPUS (YANG SUDAH ADA) --}}
                                    <form action="{{ route('admin.tps.destroy', $tps->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button type="submit" variant="ghost" size="sm" icon="trash" class="text-zinc-400 hover:text-red-600" />
                                    </form>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center py-20 text-zinc-400 italic">
                                Belum ada data TPS.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="p-4 border-t border-zinc-100">
                {{ $tps_data->links() }}
            </div>
        </flux:card>
    </div>

    {{-- MODAL TAMBAH TPS --}}
    <flux:modal name="create-tps" class="max-w-xl">
        <form action="{{ route('admin.tps.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <flux:heading size="lg">Tambah Titik TPS Baru</flux:heading>
                <flux:subheading>Cari nama jalan atau geser pin di peta satelit.</flux:subheading>
            </div>

            <div class="space-y-4">
                <flux:input label="Nama TPS" name="nama_tps" placeholder="Contoh: TPS Depo Brok" required />
                <flux:input label="Alamat" name="alamat" id="alamat_input" placeholder="Alamat otomatis terisi saat pilih peta..." />
                
                {{-- AREA PETA GOOGLE SATELLITE STYLE --}}
                <div class="space-y-2">
                    <flux:label>Pilih Lokasi TPS</flux:label>
                    <div id="map-picker"></div>
                </div>

                {{-- Hidden input koordinat --}}
                <input type="hidden" name="lat" id="lat" value="-7.0454">
                <input type="hidden" name="lng" id="lng" value="112.7441">

                <div class="grid grid-cols-2 gap-4">
                    <flux:select label="Kecamatan" name="kecamatan" required>
                        <option value="socah">Socah</option>
                        <option value="bangkalan">Bangkalan</option>
                        <option value="kamal">Kamal</option>
                    </flux:select>
                    <flux:input label="Jadwal Angkut" name="jadwal" placeholder="Senin & Kamis (08:00)" />
                </div>
            </div>

            <div class="flex gap-2 pt-4">
                <flux:modal.close class="flex-1">
                    <flux:button variant="ghost" class="w-full">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="flex-1 bg-emerald-600">Simpan Lokasi TPS</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- 3. JAVASCRIPT LOGIC --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-geosearch@3.11.0/dist/bundle.min.js"></script>

    <script>
        let map, marker;

        function initMapSystem() {
            // Jika peta sudah ada, kita hapus agar tidak error saat buka ulang modal
            if (map != undefined) { map.remove(); }

            // Fokus ke Bangkalan
            map = L.map('map-picker').setView([-7.045484, 112.744116], 16);

            // PAKAI LAYER GOOGLE MAPS HYBRID (Satelit + Jalan)
            // Biar admin bisa liat bangunan/tong sampah asli dari langit
            L.tileLayer('https://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains:['mt0','mt1','mt2','mt3']
            }).addTo(map);

            // Marker Merah Utama
            marker = L.marker([-7.045484, 112.744116], {
                draggable: true
            }).addTo(map);

            // Tambahkan Fitur Cari Alamat/Jalan
            const searchControl = new GeoSearch.GeoSearchControl({
                provider: new GeoSearch.OpenStreetMapProvider(),
                style: 'bar',
                showMarker: false,
                autoClose: true,
                searchLabel: 'Cari nama jalan/lokasi...'
            });
            map.addControl(searchControl);

            // Fungsi Update Input & Ambil Alamat Otomatis
            function updateData(lat, lng) {
                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;

                // Ambil alamat dari koordinat (Reverse Geocoding)
                fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                    .then(res => res.json())
                    .then(data => {
                        if(data.display_name) {
                            document.getElementById('alamat_input').value = data.display_name.substring(0, 90);
                        }
                    });
            }

            // Event: Pin Digeser
            marker.on('dragend', function(e) {
                let pos = marker.getLatLng();
                updateData(pos.lat, pos.lng);
            });

            // Event: Peta Diklik
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateData(e.latlng.lat, e.latlng.lng);
            });

            // Event: Hasil Pencarian Dipilih
            map.on('geosearch/showlocation', function(e) {
                const results = e.location;
                marker.setLatLng([results.y, results.x]);
                updateData(results.y, results.x);
            });

            // Paksa refresh ukuran peta agar tidak abu-abu
            setTimeout(() => { map.invalidateSize(); }, 300);
        }

        // Fungsi yang dipanggil saat tombol "Tambah TPS" diklik
        function startMapProcess() {
            setTimeout(() => {
                initMapSystem();
            }, 400); // Delay agar modal selesai muncul dulu
        }
    </script>
</x-layouts::app>