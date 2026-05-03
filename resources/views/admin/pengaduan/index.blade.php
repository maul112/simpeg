<x-layouts::app :title="__('Manajemen Pengaduan')">
    <div class="p-8 max-w-7xl mx-auto space-y-8">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-end gap-4">
            <div>
                <flux:heading size="xl" class="font-bold tracking-tight">Data Pengaduan</flux:heading>
                <flux:subheading class="mt-1 text-zinc-500">Kelola dan tindak lanjuti laporan kebersihan masyarakat secara real-time.</flux:subheading>
            </div>
            
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
            {{-- Filter Bar --}}
            <div class="p-4 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50">
                <form action="{{ route('admin.pengaduan.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[300px]">
                        <flux:input name="search" icon="magnifying-glass" value="{{ request('search') }}" 
                            placeholder="Cari berdasarkan nama pelapor atau lokasi..." 
                            class="bg-white dark:bg-zinc-950 border-zinc-200" />
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
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="pl-6">Laporan & Tanggal</flux:table.column>
                    <flux:table.column>Pelapor</flux:table.column>
                    <flux:table.column>Tipe</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column>Komentar</flux:table.column>
                    <flux:table.column align="right" class="pr-6">Opsi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($reports as $report)
                        <flux:table.row class="hover:bg-zinc-50/80 transition-colors">
                            <flux:table.cell class="pl-6">
                                <div class="flex items-center gap-4">
                                    @if($report->foto_bukti)
                                        <img src="{{ asset('storage/' . $report->foto_bukti) }}" class="w-10 h-10 rounded-lg object-cover ring-1 ring-zinc-200">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-zinc-100 flex items-center justify-center text-zinc-400">
                                            <flux:icon.camera class="w-4 h-4" />
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-semibold text-zinc-900">#REP-{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</div>
                                        <div class="text-[10px] text-zinc-400 uppercase tracking-wide">{{ $report->created_at->translatedFormat('d M Y') }}</div>
                                    </div>
                                </div>
                            </flux:table.cell>

                            <flux:table.cell>
                                <div class="text-sm font-medium">{{ $report->nama_pelapor }}</div>
                                <div class="text-xs text-zinc-400 italic truncate max-w-[120px]">{{ $report->lokasi_manual }}</div>
                            </flux:table.cell>

                            <flux:table.cell>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $report->tipe_sampah == 'organik' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                    {{ $report->tipe_sampah }}
                                </span>
                            </flux:table.cell>

                            <flux:table.cell>
                                <flux:badge size="sm" :color="match($report->status) { 'pending' => 'red', 'proses' => 'blue', 'selesai' => 'green', default => 'zinc' }" variant="solid" class="capitalize rounded-md">
                                    {{ $report->status }}
                                </flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex items-center gap-1.5 text-zinc-500">
                                    <flux:icon.chat-bubble-left-right class="w-4 h-4" />
                                    {{-- Menggunakan comments_count dari withCount() --}}
                                    <span class="text-xs font-bold">{{ $report->comments_count ?? 0 }}</span>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell align="right" class="pr-6">
                                <div class="flex justify-end gap-1">
                                    <flux:modal.trigger name="detail-{{ $report->id }}">
                                        <flux:button variant="ghost" size="sm" icon="eye" class="btn-load-map" data-report-id="{{ $report->id }}" data-latitude="{{ $report->latitude }}" data-longitude="{{ $report->longitude }}" />
                                    </flux:modal.trigger>
                                    <flux:modal.trigger name="delete-{{ $report->id }}">
                                        <flux:button variant="ghost" size="sm" icon="trash" class="text-zinc-400 hover:text-red-600" />
                                    </flux:modal.trigger>
                                </div>

                                {{-- MODAL DETAIL (KOLOM RAPI) --}}
                                <flux:modal name="detail-{{ $report->id }}" variant="flyout" class="w-full max-w-2xl p-0 overflow-hidden">
                                    <div class="flex flex-col h-screen bg-white">
                                        <div class="p-6 border-b bg-zinc-50/50">
                                            <flux:heading size="lg" class="font-bold">Detail Laporan #{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</flux:heading>
                                            <flux:subheading>Informasi lengkap dan tanggapan petugas.</flux:subheading>
                                        </div>

                                        <div class="flex-1 overflow-y-auto p-6 space-y-6">
                                            {{-- Grid Atas: Info Dasar --}}
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="p-3 rounded-xl bg-zinc-50 border border-zinc-100 text-left">
                                                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Pelapor</p>
                                                    <p class="text-sm font-semibold text-zinc-800 truncate">{{ $report->nama_pelapor }}</p>
                                                </div>
                                                <div class="p-3 rounded-xl bg-zinc-50 border border-zinc-100 text-left">
                                                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Waktu Lapor</p>
                                                    <p class="text-sm font-semibold text-zinc-800">{{ $report->created_at->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>

                                            {{-- Grid Tengah: Media & Map Bersampingan --}}
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="space-y-1 text-left">
                                                    <p class="text-[10px] font-bold text-zinc-400 uppercase px-1">Foto Bukti</p>
                                                    <div class="rounded-xl overflow-hidden aspect-video bg-zinc-100 border">
                                                        @if($report->foto_bukti)
                                                            <img src="{{ asset('storage/' . $report->foto_bukti) }}" class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center text-zinc-300 text-xs italic">Tidak ada foto</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="space-y-1 text-left">
                                                    <p class="text-[10px] font-bold text-zinc-400 uppercase px-1">Peta Lokasi</p>
                                                    <div id="map-container-{{ $report->id }}" class="rounded-xl aspect-video bg-zinc-100 border overflow-hidden"></div>
                                                </div>
                                            </div>

                                            {{-- Deskripsi --}}
                                            <div class="p-4 rounded-xl border border-dashed border-zinc-200 text-left bg-zinc-50/30">
                                                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Keterangan Laporan</p>
                                                <p class="text-sm text-zinc-600 leading-relaxed italic">"{{ $report->deskripsi }}"</p>
                                                <div class="mt-2 flex items-center gap-1 text-[11px] text-zinc-400">
                                                    <flux:icon.map-pin class="w-3 h-3 shrink-0" />
                                                    <span class="truncate">{{ $report->lokasi_manual }}</span>
                                                </div>
                                            </div>

                                            <hr class="border-zinc-100">

                                            {{-- BAGIAN DISKUSI / KOMENTAR --}}
                                            <div class="space-y-4 text-left">
                                                <div class="flex items-center gap-2">
                                                    <flux:icon.chat-bubble-bottom-center-text class="w-4 h-4 text-zinc-400" />
                                                    <p class="text-sm font-bold text-zinc-700">Tanggapan & Diskusi</p>
                                                </div>

                                                <div class="space-y-3">
                                                    @forelse($report->comments as $comment)
                                                        <div class="flex gap-3 {{ $comment->user_id == Auth::id() ? 'flex-row-reverse text-right' : '' }}">
                                                            <div class="w-7 h-7 rounded-full bg-emerald-500 flex-shrink-0 flex items-center justify-center text-white font-bold text-[10px]">
                                                                {{ substr($comment->user->name ?? 'A', 0, 1) }}
                                                            </div>
                                                            <div class="max-w-[85%]">
                                                                <div class="p-3 rounded-2xl text-xs {{ $comment->user_id == Auth::id() ? 'bg-emerald-600 text-white rounded-tr-none' : 'bg-zinc-100 text-zinc-700 rounded-tl-none' }}">
                                                                    {{ $comment->body }}
                                                                </div>
                                                                <p class="text-[9px] text-zinc-400 mt-1 uppercase">{{ $comment->created_at->diffForHumans() }}</p>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="text-center py-4 bg-zinc-50 rounded-xl border border-zinc-100 text-[11px] text-zinc-400 italic">
                                                            Belum ada tanggapan.
                                                        </div>
                                                    @endforelse
                                                </div>

                                                {{-- Input Chat --}}
                                                <form action="{{ route('admin.pengaduan.comment', $report->id) }}" method="POST" class="relative mt-2">
                                                    @csrf
                                                    <textarea name="body" rows="2" placeholder="Tulis tanggapan atau instruksi..." 
                                                        class="w-full p-3 pr-12 text-xs bg-white border border-zinc-200 rounded-xl focus:ring-1 focus:ring-emerald-500 outline-none transition-all resize-none"></textarea>
                                                    <button type="submit" class="absolute right-2 bottom-2 p-1.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                                        <flux:icon.paper-airplane class="w-3.5 h-3.5" />
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        {{-- Footer Action --}}
                                        <div class="p-6 border-t bg-zinc-50">
                                            <form action="{{ route('admin.pengaduan.status', $report->id) }}" method="POST" class="flex items-center gap-2">
                                                @csrf @method('PATCH')
                                                <div class="flex-1">
                                                    <flux:select name="status">
                                                        <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>🔴 Pending</option>
                                                        <option value="proses" {{ $report->status == 'proses' ? 'selected' : '' }}>🔵 Diproses</option>
                                                        <option value="selesai" {{ $report->status == 'selesai' ? 'selected' : '' }}>🟢 Selesai</option>
                                                    </flux:select>
                                                </div>
                                                <flux:button type="submit" variant="primary" class="bg-emerald-600">Update</flux:button>
                                            </form>
                                        </div>
                                    </div>
                                </flux:modal>

                                {{-- MODAL DELETE --}}
                                <flux:modal name="delete-{{ $report->id }}" class="max-w-xs">
                                    <div class="text-center p-4">
                                        <flux:heading size="lg">Hapus Laporan?</flux:heading>
                                        <p class="text-xs text-zinc-500 mt-2">Tindakan ini tidak bisa dibatalkan.</p>
                                        <div class="flex gap-2 mt-6">
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
                            <flux:table.cell colspan="6" class="text-center py-20 text-zinc-400 italic">Tidak ada pengaduan ditemukan</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="p-6 border-t border-zinc-100">
                {{ $reports->links() }}
            </div>
        </flux:card>
    </div>

    {{-- Leaflet Script --}}
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
                const map = L.map(containerId, { zoomControl: false, attributionControl: false }).setView([lat, lng], 16);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);
                L.marker([lat, lng]).addTo(map);
                activeMaps[id] = map;
            }, 300);
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.btn-load-map').forEach(button => {
                button.addEventListener('click', () => {
                    loadMap(button.dataset.reportId, button.dataset.latitude, button.dataset.longitude);
                });
            });
        });
    </script>
</x-layouts::app>