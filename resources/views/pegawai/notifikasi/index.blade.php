<x-layouts::pegawai_app :title="__('Notifikasi')">

    <x-floating-managed-message />

    <flux:card class="relative overflow-hidden border-none bg-emerald-600 text-white shadow-md p-8!">
        {{-- Efek dekorasi background --}}
        <div class="absolute -right-10 -top-20 opacity-10">
            <flux:icon.globe-americas class="w-64 h-64" />
        </div>

        <div class="relative z-10">
            <flux:subheading class="text-emerald-100 mb-1">Selamat datang kembali,</flux:subheading>
            <flux:heading size="2xl" class="text-white mb-4">{{ $employee->name }}</flux:heading>

            <div class="flex flex-wrap gap-4 text-sm mt-4">
                <div class="flex items-center gap-1.5 bg-emerald-700/50 px-3 py-1.5 rounded-md">
                    <flux:icon.identification class="w-4 h-4 text-emerald-300" />
                    <span>NIP. {{ $employee->nip ?? '-' }}</span>
                </div>
                <div class="flex items-center gap-1.5 bg-emerald-700/50 px-3 py-1.5 rounded-md">
                    <flux:icon.briefcase class="w-4 h-4 text-emerald-300" />
                    <span>{{ $employee->type }} ({{ $employee->gender == 'l' ? 'Laki-laki' : 'Perempuan' }})</span>
                </div>
            </div>
        </div>
    </flux:card>

    {{-- Daftar Notifikasi --}}
    <div class="mt-6 space-y-4">
        @forelse($notifications as $notif)
            @if ($notif->type == 'pangkat')
                <a href="{{ route('pegawai.notifikasi.show', $notif->id) }}" class="block">
                    <x-notification :notif="$notif" />
                </a>
            @else
                <x-notification :notif="$notif" />
            @endif
        @empty
            {{-- Tampilan Kosong (Empty State) yang Rapi --}}
            <div class="text-center py-16 text-zinc-500 bg-zinc-50 dark:bg-zinc-800 rounded-xl border border-dashed border-zinc-200 mt-6">
                <svg class="mx-auto h-12 w-12 text-zinc-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <p class="font-medium dark:text-white">Belum ada notifikasi.</p>
                <p class="text-sm mt-1 dark:text-white">Anda sudah membaca semua pembaruan sistem.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</x-layouts::pegawai_app>