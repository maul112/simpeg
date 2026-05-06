<div
    class="relative p-5 border rounded-xl flex flex-col sm:flex-row gap-4 sm:items-center transition-all 
                                                                                            {{ $notif->is_read ? 'bg-white dark:bg-zinc-800 border-zinc-200' : 'bg-emerald-50/40 border-emerald-200 shadow-sm' }}">

    {{-- Indikator Titik Biru untuk pesan yang belum dibaca --}}
    @if(!$notif->is_read)
        <div class="absolute top-5 right-5 w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></div>
    @endif

    <div class="flex-1">
        {{-- Badge Tipe & Waktu --}}
        <div class="flex items-center gap-2 mb-1.5">
            <flux:badge size="sm" color="{{ $notif->is_read ? 'zinc' : 'emerald' }}" class="dark:text-white">
                {{ Str::headline($notif->type) }}
            </flux:badge>
            {{-- Mengubah timestamp menjadi format ramah seperti "2 jam yang lalu" --}}
            <span class="text-xs dark:text-white {{ $notif->is_read ? 'text-zinc-500' : 'text-emerald-600 font-medium' }}">
                {{ $notif->created_at->diffForHumans() }}
            </span>
        </div>

        {{-- Judul & Pesan --}}
        <h3 class="text-base font-semibold dark:text-white {{ $notif->is_read ? 'text-zinc-800' : 'text-emerald-900' }}">
            {{ $notif->title }}
            @if ($notif->type == 'pangkat' && $notif->status != null)
                @if ($notif->status == 'pending')
                    (Pending)
                @elseif ($notif->status == 'approved')
                    (Disetujui)
                @else
                    (Ditolak)
                @endif
            @endif
        </h3>
        <p class="text-sm mt-1 dark:text-white {{ $notif->is_read ? 'text-zinc-600' : 'text-emerald-800' }}">
            {{ $notif->message }}
        </p>
    </div>

    {{-- Tombol Aksi (Hanya muncul jika belum dibaca) --}}
    @if(!$notif->is_read)
        <flux:modal.trigger name="mark_is_read-{{ $notif->id }}" onclick="event.stopPropagation(); event.preventDefault();">
            <flux:button size="sm" variant="outline" class="w-full sm:w-auto cursor-pointer">
                Tandai Dibaca
            </flux:button>
        </flux:modal.trigger>
        <flux:modal name="mark_is_read-{{ $notif->id }}" class="min-w-88">
            {{-- Form diarahkan ke fungsi destroy di Controller --}}
            <form action="{{ route('pegawai.notifikasi.read', $notif->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">Tandai sebagai Dibaca</flux:heading>
                        <flux:subheading>
                            Apakah Anda yakin ingin menandai notifikasi ini sebagai dibaca?
                        </flux:subheading>
                    </div>

                    <div class="flex justify-end gap-2">
                        <flux:modal.close>
                            <flux:button variant="ghost">Batal</flux:button>
                        </flux:modal.close>

                        <flux:button type="submit" color="emerald" variant="primary" class="cursor-pointer">
                            Ya, Tandai Dibaca
                        </flux:button>
                    </div>
                </div>
            </form>
        </flux:modal>
    @endif
</div>