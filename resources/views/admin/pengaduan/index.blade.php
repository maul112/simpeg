<x-layouts::app :title="__('Manajemen Pengaduan')">
    <div class="p-8 max-w-7xl mx-auto space-y-8">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-end gap-4">
            <div>
                <flux:heading size="xl" class="font-bold tracking-tight">Data Pengaduan</flux:heading>
                <flux:subheading class="mt-1 text-zinc-500">Kelola dan tindak lanjuti laporan kebersihan masyarakat secara real-time.</flux:subheading>
            </div>
            
            {{-- Flash Notification (Floating Style) --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
                    class="fixed top-6 right-6 z-[100] flex items-center gap-3 px-5 py-3 bg-white dark:bg-zinc-900 border border-emerald-200 dark:border-emerald-800 shadow-xl rounded-2xl">
                    <div class="p-1.5 bg-emerald-500 rounded-full text-white">
                        <flux:icon.check class="w-4 h-4" />
                    </div>
                    <span class="text-sm font-medium text-emerald-800 dark:text-emerald-400">{{ session('success') }}</span>
                </div>
            @endif
        </div>

        <flux:card class="overflow-hidden border-none shadow-sm ring-1 ring-zinc-200 dark:ring-zinc-800">
            {{-- Filter & Action Bar --}}
            <div class="p-4 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50">
                <form action="{{ route('admin.pengaduan.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[300px]">
                        <flux:input name="search" icon="magnifying-glass" value="{{ request('search') }}" 
                            placeholder="Cari berdasarkan nama pelapor atau lokasi..." 
                            class="bg-white dark:bg-zinc-950 border-zinc-200 focus:ring-emerald-500" />
                    </div>

                    <div class="flex items-center gap-2">
                        <flux:select name="tipe_sampah" onchange="this.form.submit()" class="min-w-[140px]">
                            <option value="">Semua Jenis</option>
                            <option value="organik" {{ request('tipe_sampah') == 'organik' ? 'selected' : '' }}>Organik</option>
                            <option value="non_organik" {{ request('tipe_sampah') == 'non_organik' ? 'selected' : '' }}>Non Organik</option>
                        </flux:select>

                        <flux:select name="status" onchange="this.form.submit()" class="min-w-[140px]">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </flux:select>

                        <flux:button type="submit" variant="primary" class="bg-emerald-600 hover:bg-emerald-700">Filter</flux:button>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="pl-6">Laporan & Tanggal</flux:table.column>
                    <flux:table.column>Pelapor</flux:table.column>
                    <flux:table.column>Lokasi</flux:table.column>
                    <flux:table.column>Tipe</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column align="right" class="pr-6">Opsi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($reports as $report)
                        <flux:table.row class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/40 transition-colors">
                            {{-- Info Laporan --}}
                            <flux:table.cell class="pl-6">
                                <div class="flex items-center gap-4">
                                    <div class="relative group cursor-pointer" x-on:click="$dispatch('modal-show', { name: 'update-status-{{ $report->id }}' })">
                                        @if($report->foto_bukti)
                                            <img src="{{ asset('storage/' . $report->foto_bukti) }}" class="w-12 h-12 rounded-xl object-cover ring-2 ring-zinc-100 dark:ring-zinc-800 shadow-sm transition group-hover:scale-105">
                                        @else
                                            <div class="w-12 h-12 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-400">
                                                <flux:icon.camera class="w-5 h-5" />
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-semibold text-zinc-900 dark:text-zinc-100">#REP-{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</div>
                                        <div class="text-xs text-zinc-500">{{ $report->created_at->translatedFormat('d M Y, H:i') }} WIB</div>
                                    </div>
                                </div>
                            </flux:table.cell>

                            {{-- Pelapor --}}
                            <flux:table.cell>
                                <div class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $report->nama_pelapor }}</div>
                                <div class="text-xs text-zinc-500 italic">{{ $report->kontak ?: 'Kontak (-)' }}</div>
                            </flux:table.cell>

                            {{-- Lokasi --}}
                            <flux:table.cell>
                                <div class="flex items-start gap-1 max-w-[200px]">
                                    <flux:icon.map-pin class="w-4 h-4 text-zinc-400 shrink-0 mt-0.5" />
                                    <span class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2" title="{{ $report->lokasi_manual }}">
                                        {{ $report->lokasi_manual }}
                                    </span>
                                </div>
                            </flux:table.cell>

                            {{-- Tipe --}}
                            <flux:table.cell>
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider 
                                    {{ $report->tipe_sampah == 'organik' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                    {{ $report->tipe_sampah }}
                                </span>
                            </flux:table.cell>

                            {{-- Status --}}
                            <flux:table.cell>
                                <flux:badge size="sm" :color="match($report->status) {
                                    'pending' => 'red',
                                    'proses' => 'blue',
                                    'selesai' => 'green',
                                    default => 'zinc'
                                }" variant="solid" class="capitalize font-medium rounded-lg">
                                    {{ $report->status }}
                                </flux:badge>
                            </flux:table.cell>

                            {{-- Aksi --}}
                            <flux:table.cell align="right" class="pr-6">
                                <div class="flex justify-end items-center gap-1">
                                    <flux:modal.trigger name="update-status-{{ $report->id }}">
                                        <flux:button variant="ghost" size="sm" icon="eye" class="text-zinc-500 hover:text-zinc-900 btn-load-map"
                                            data-report-id="{{ $report->id }}"
                                            data-latitude="{{ $report->latitude }}"
                                            data-longitude="{{ $report->longitude }}" />
                                    </flux:modal.trigger>
                                    
                                    <flux:modal.trigger name="delete-report-{{ $report->id }}">
                                        <flux:button variant="ghost" size="sm" icon="trash" class="text-zinc-400 hover:text-red-600" />
                                    </flux:modal.trigger>
                                </div>

                                {{-- MODAL DETAIL (ELEGANT VERSION) --}}
                                <flux:modal name="update-status-{{ $report->id }}" variant="flyout" class="space-y-6">
                                    <div class="flex flex-col h-full">
                                        <div class="mb-6">
                                            <flux:heading size="xl" class="font-bold">Detail Laporan</flux:heading>
                                            <flux:subheading>Informasi lengkap terkait pengaduan kebersihan.</flux:subheading>
                                        </div>

                                        <div class="space-y-8 flex-1">
                                            {{-- Info Cards --}}
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="p-4 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-700">
                                                    <span class="text-xs text-zinc-500 uppercase font-bold tracking-widest">Pelapor</span>
                                                    <p class="font-semibold text-zinc-900 dark:text-white mt-1">{{ $report->nama_pelapor }}</p>
                                                </div>
                                                <div class="p-4 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-700">
                                                    <span class="text-xs text-zinc-500 uppercase font-bold tracking-widest">Waktu</span>
                                                    <p class="font-semibold text-zinc-900 dark:text-white mt-1">{{ $report->created_at->format('H:i, d M Y') }}</p>
                                                </div>
                                            </div>

                                            {{-- Deskripsi --}}
                                            <div>
                                                <span class="text-xs text-zinc-500 uppercase font-bold tracking-widest">Keterangan</span>
                                                <p class="mt-2 text-zinc-700 dark:text-zinc-300 leading-relaxed">{{ $report->deskripsi }}</p>
                                            </div>

                                            {{-- Media & Maps --}}
                                            <div class="space-y-4">
                                                @if($report->foto_bukti)
                                                    <div class="rounded-3xl overflow-hidden shadow-lg border border-zinc-200">
                                                        <img src="{{ asset('storage/' . $report->foto_bukti) }}" class="w-full h-48 object-cover">
                                                    </div>
                                                @endif
                                                
                                                <div id="map-container-{{ $report->id }}" class="h-48 w-full rounded-3xl bg-zinc-100 overflow-hidden ring-1 ring-zinc-200"></div>
                                            </div>

                                            {{-- Action Form --}}
                                            <form action="{{ route('admin.pengaduan.status', $report->id) }}" method="POST" class="pt-6 border-t border-zinc-100 dark:border-zinc-800">
                                                @csrf @method('PATCH')
                                                <flux:select name="status" label="Update Status Laporan">
                                                    <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>🔴 Pending</option>
                                                    <option value="proses" {{ $report->status == 'proses' ? 'selected' : '' }}>🔵 Diproses</option>
                                                    <option value="selesai" {{ $report->status == 'selesai' ? 'selected' : '' }}>🟢 Selesai</option>
                                                </flux:select>
                                                <div class="mt-6 flex gap-3">
                                                    <flux:button type="submit" variant="primary" class="flex-1 py-3 bg-emerald-600">Simpan Perubahan</flux:button>
                                                    <flux:modal.close><flux:button variant="ghost">Batal</flux:button></flux:modal.close>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </flux:modal>

                                {{-- MODAL DELETE --}}
                                <flux:modal name="delete-report-{{ $report->id }}" class="max-w-xs">
                                    <div class="text-center p-4">
                                        <div class="w-16 h-16 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <flux:icon.trash class="w-8 h-8" />
                                        </div>
                                        <flux:heading size="lg">Hapus Data?</flux:heading>
                                        <p class="text-sm text-zinc-500 mt-2 leading-snug">Laporan #{{ $report->id }} akan dihapus permanen dari sistem.</p>
                                        <div class="flex gap-2 mt-8">
                                            <flux:modal.close><flux:button variant="ghost" class="flex-1">Batal</flux:button></flux:modal.close>
                                            <form action="{{ route('admin.pengaduan.destroy', $report->id) }}" method="POST" class="flex-1">
                                                @csrf @method('DELETE')
                                                <flux:button type="submit" variant="danger" class="w-full">Hapus</flux:button>
                                            </form>
                                        </div>
                                    </div>
                                </flux:modal>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="6" class="text-center py-20 text-zinc-400">
                                <flux:icon.inbox class="w-12 h-12 mx-auto mb-3 opacity-20" />
                                <p class="text-lg font-medium">Tidak ada pengaduan ditemukan</p>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="p-6 border-t border-zinc-100 dark:border-zinc-800">
                {{ $reports->links() }}
            </div>
        </flux:card>
    </div>

    {{-- Script Leaflet Tetap Sama --}}
    <script>
        let activeMaps = {};

        function loadMap(id, lat, lng) {
            if (!lat || !lng) return;

            setTimeout(() => {
                const containerId = 'map-container-' + id;
                if (activeMaps[id]) {
                    activeMaps[id].invalidateSize();
                    return;
                }

                const map = L.map(containerId, { zoomControl: false }).setView([lat, lng], 16);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);
                L.marker([lat, lng]).addTo(map);
                activeMaps[id] = map;
            }, 300);
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.btn-load-map').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.dataset.reportId;
                    const lat = button.dataset.latitude;
                    const lng = button.dataset.longitude;
                    loadMap(id, lat, lng);
                });
            });
        });
    </script>
</x-layouts::app>