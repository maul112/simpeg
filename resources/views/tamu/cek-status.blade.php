<x-layouts::app.landing :title="__('Portal Laporan - DLH Bangkalan')">
    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto font-sans">

        {{-- Header Portal --}}
        <div class="text-center mb-12">
            <div class="flex flex-col items-center justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo DLH" class="h-20 w-auto object-contain mb-4">
                <h1 class="text-lg font-black tracking-widest text-zinc-900 dark:text-white uppercase">
                    Dinas Lingkungan Hidup
                </h1>
                <div class="h-1 w-12 bg-emerald-500 mt-2 rounded-full"></div>
            </div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mt-4">Portal Transparansi Laporan</h1>
            <p class="mt-3 text-zinc-500 dark:text-zinc-400 max-w-xl mx-auto text-sm">
                Daftar aspirasi dan laporan kebersihan masyarakat wilayah Bangkalan yang sedang diproses petugas.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="flex justify-between items-center mb-2 px-1">
                    <h3 class="font-bold text-zinc-800 dark:text-white flex items-center gap-2 uppercase text-xs tracking-widest">
                        <flux:icon.chat-bubble-left-right class="w-5 h-5 text-emerald-500" /> Laporan Terbaru
                    </h3>
                    <flux:button href="{{ route('pengaduan.create') }}" size="sm" variant="primary" class="bg-amber-500 hover:bg-amber-600 border-none font-bold text-[10px] shadow-sm">
                        + BUAT PENGADUAN
                    </flux:button>
                </div>

                @forelse($allReports as $item)
                    <flux:card class="p-0 overflow-hidden border-zinc-200 dark:border-zinc-800 shadow-sm hover:shadow-md transition-shadow">
                        
                        <div class="bg-zinc-100/80 dark:bg-zinc-900/50 border-b border-zinc-200 dark:border-zinc-800 px-5 py-2.5 flex justify-between items-center text-[10px]">
                            <span class="font-black text-emerald-700 dark:text-emerald-500 flex items-center gap-1.5 uppercase tracking-[0.15em]">
                                <flux:icon.megaphone class="w-3.5 h-3.5"/> Pengaduan
                            </span>
                            <span class="text-zinc-500 font-medium flex items-center gap-1">
                                <flux:icon.clock class="w-3.5 h-3.5"/> {{ $item->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <div class="p-5">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center text-white font-bold shadow-inner">
                                    {{ substr($item->nama_pelapor ?? 'N', 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-zinc-900 dark:text-white">
                                        {{ Str::mask($item->nama_pelapor ?? 'Masyarakat', '*', 2) }}
                                    </h4>
                                    <p class="text-[10px] text-zinc-400 uppercase font-bold tracking-tighter">Warga Bangkalan</p>
                                </div>
                            </div>
                            
                            <div class="mb-5 px-1">
                                <p class="text-zinc-700 dark:text-zinc-300 text-sm leading-relaxed italic">
                                    "{{ $item->deskripsi }}"
                                </p>
                            </div>
                            
                            {{-- BOX INFORMASI --}}
                            <div class="bg-zinc-50 dark:bg-zinc-900/30 rounded-xl p-3 border border-zinc-100 dark:border-zinc-800 flex flex-col md:flex-row gap-4">
                                {{-- CONTAINER FOTO: Kunci ukuran 128px --}}
                                @php $placeholder = asset('images/placeholder.png'); @endphp
                                <div class="flex-none" style="width: 128px; height: 128px;">
                                    <div class="relative w-full h-full rounded-md overflow-hidden border border-zinc-300 bg-zinc-200">
                                        @if($item->foto_bukti)
                                            <a href="{{ Storage::url($item->foto_bukti) }}" target="_blank" class="block w-full h-full">
                                                <img src="{{ Storage::url($item->foto_bukti) }}" 
                                                    class="absolute inset-0 w-full h-full object-cover object-center"
                                                    onerror="this.onerror=null; this.src='{{ $placeholder }}'; ">
                                            </a>
                                        @else
                                            <div class="absolute inset-0 flex items-center justify-center text-zinc-400 text-[9px] font-bold">
                                                TANPA FOTO
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- DETAIL TEXT --}}
                                <div class="flex-1 space-y-2 py-1">
                                    <span class="inline-block px-2 py-0.5 bg-blue-600 text-white text-[9px] font-black rounded uppercase tracking-wider mb-1">
                                        Informasi
                                    </span>
                                    <div class="text-[11px] space-y-2 text-zinc-600 dark:text-zinc-400 font-medium">
                                        <div class="flex items-center gap-2">
                                            <flux:icon.tag class="w-3.5 h-3.5 text-zinc-400"/>
                                            <span>Jenis: <span class="text-zinc-900 dark:text-white font-bold">{{ Str::headline($item->tipe_sampah) }}</span></span>
                                        </div>
                                        <div class="flex items-start gap-2 leading-tight">
                                            <flux:icon.map-pin class="w-3.5 h-3.5 text-zinc-400 shrink-0 mt-0.5"/>
                                            <span class="line-clamp-2">Lokasi: <span class="text-zinc-900 dark:text-white font-bold">{{ $item->lokasi_manual ?? 'Area Bangkalan' }}</span></span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            {{-- STATUS LOGIKA ADMIN --}}
                                            <flux:icon.check-circle class="w-3.5 h-3.5 {{ $item->status === 'selesai' ? 'text-emerald-500' : 'text-blue-500' }}"/>
                                            <span class="font-bold uppercase tracking-tighter {{ $item->status === 'selesai' ? 'text-emerald-600' : 'text-blue-600' }}">
                                                {{ $item->status === 'selesai' ? 'Tervalidasi Selesai' : 'Terverifikasi Sistem' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pt-4 border-t border-zinc-100 dark:border-zinc-800 mt-3">
                                <flux:button variant="subtle" size="xs" icon="share" class="text-zinc-400 border shadow-none px-3">Share</flux:button>
                                <div class="flex items-center gap-1.5">
                                    {{-- STATUS WARNA SESUAI LOGIKA ADMIN --}}
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter shadow-sm
                                        {{ $item->status === 'selesai' ? 'bg-emerald-500 text-white' : ($item->status === 'proses' ? 'bg-blue-500 text-white' : 'bg-zinc-200 text-zinc-500 dark:bg-zinc-800') }}">
                                        {{ $item->status === 'selesai' ? 'Selesai' : ($item->status === 'proses' ? 'Diproses' : 'Menunggu') }}
                                    </span>
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter bg-orange-500 text-white shadow-sm">
                                        {{ $item->comments_count ?? 0 }} Komentar
                                    </span>
                                </div>
                            </div>
                        </div>
                    </flux:card>
                @empty
                    <div class="text-center py-16 border-2 border-dashed border-zinc-200 dark:border-zinc-800 rounded-2xl">
                        <p class="text-zinc-500 text-sm italic">Belum ada laporan publik untuk ditampilkan.</p>
                    </div>
                @endforelse
            </div>

            <div class="space-y-6">
                <flux:card class="p-5 border-zinc-200 shadow-sm border-t-4 border-t-emerald-600">
                    <h3 class="font-bold text-zinc-900 dark:text-white text-[11px] mb-5 flex items-center gap-2 border-b pb-3 uppercase tracking-widest">
                        <flux:icon.information-circle class="w-4 h-4 text-emerald-500" /> Alur Kerja
                    </h3>
                    <div class="space-y-4">
                        @foreach(['Laporan Masuk', 'Verifikasi Admin', 'Petugas Meluncur'] as $index => $step)
                        <div class="flex gap-3">
                            <div class="flex-none w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] font-black">{{ $index + 1 }}</div>
                            <div class="text-[10px] text-zinc-600 dark:text-zinc-400">
                                <span class="font-bold text-zinc-900 dark:text-white block uppercase">{{ $step }}</span>
                                Proses transparansi laporan warga.
                            </div>
                        </div>
                        @endforeach
                    </div>
                </flux:card>
            </div>
        </div>
    </div>
</x-layouts::app.landing>