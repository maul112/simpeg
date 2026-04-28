<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- Header Ringkas --}}
        <div>
            <flux:heading size="xl">Dashboard Admin</flux:heading>
            @if (auth()->user()->role == "admin_simpeg")
                <flux:subheading>Ringkasan sistem kepegawaian hari ini.</flux:subheading>
            @elseif (auth()->user()->role == "admin_sampah")
                <flux:subheading>Ringkasan sistem laporan masyarakat hari ini.</flux:subheading>
            @endif
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            {{-- ===================== ADMIN SIMPEG ===================== --}}
            @if (auth()->user()->role === 'admin_simpeg')

                <flux:card class="flex items-center gap-4 p-6! border-l-4 border-l-blue-500">
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                        <flux:icon.users class="w-8 h-8" />
                    </div>
                    <div>
                        <flux:subheading>Jumlah Pegawai</flux:subheading>
                        <flux:heading size="xl">
                            {{ $totalPegawai }}
                            <span class="text-sm text-zinc-500">Orang</span>
                        </flux:heading>
                    </div>
                </flux:card>

                <flux:card class="flex items-center gap-4 p-6! border-l-4 border-l-emerald-500">
                    <div class="bg-emerald-100 text-emerald-600 p-3 rounded-full">
                        <flux:icon.user-group class="w-8 h-8" />
                    </div>
                    <div>
                        <flux:subheading>Pegawai ASN</flux:subheading>
                        <flux:heading size="xl">
                            {{ $asnCount }}
                            <span class="text-sm text-zinc-500">Orang</span>
                        </flux:heading>
                    </div>
                </flux:card>

                <flux:card class="flex items-center gap-4 p-6! border-l-4 border-l-red-500">
                    <div class="bg-red-100 text-red-600 p-3 rounded-full">
                        <flux:icon.bell-alert class="w-8 h-8" />
                    </div>
                    <div>
                        <flux:subheading>Jumlah Notifikasi</flux:subheading>
                        <flux:heading size="xl">
                            {{ $notifCount }}
                            <span class="text-sm text-zinc-500">Notifikasi</span>
                        </flux:heading>
                    </div>
                </flux:card>

            @endif


            {{-- ===================== ADMIN DLH ===================== --}}
            @if (auth()->user()->role === 'admin_sampah')

                <flux:card class="flex items-center gap-4 p-6! border-l-4 border-l-red-500">
                    <div class="bg-red-100 text-red-600 p-3 rounded-full">
                        <flux:icon.bell-alert class="w-8 h-8" />
                    </div>
                    <div>
                        <flux:subheading>Laporan Pending</flux:subheading>
                        <flux:heading size="xl">
                            {{ $pendingReportsCount }}
                            <span class="text-sm text-zinc-500">Laporan</span>
                        </flux:heading>
                    </div>
                </flux:card>

                <flux:card class="flex items-center gap-4 p-6! border-l-4 border-l-emerald-500">
                    <div class="bg-emerald-100 text-emerald-600 p-3 rounded-full">
                        <flux:icon.user-group class="w-8 h-8" />
                    </div>
                    <div>
                        <flux:subheading>Jumlah Laporan</flux:subheading>
                        <flux:heading size="xl">
                            {{ $allReport }}
                            <span class="text-sm text-zinc-500">Jumlah</span>
                        </flux:heading>
                    </div>
                </flux:card>

                <flux:card class="flex items-center gap-4 p-6! border-l-4 border-l-green-500">
                    <div class="bg-green-100 text-green-600 p-3 rounded-full dark:bg-green-900/30 dark:text-green-400">
                        <flux:icon.check-circle class="w-8 h-8" />
                    </div>
                    <div>
                        <flux:subheading>Laporan Selesai</flux:subheading>
                        <flux:heading size="xl">
                            {{ $resolvedCount }}
                            <span class="text-sm font-normal text-zinc-500 dark:text-zinc-400">
                                Laporan
                            </span>
                        </flux:heading>
                    </div>
                </flux:card>

            @endif

        </div>

        {{-- ===================== BOTTOM SECTION ===================== --}}

        {{-- 🔵 ADMIN SIMPEG → NOTIF --}}
        @if (auth()->user()->role === 'admin_simpeg')

            <flux:card class="relative flex-1 flex flex-col">
                <div class="flex items-center justify-between border-b pb-4 mb-4">
                    <div>
                        <flux:heading size="lg">Notifikasi Pegawai</flux:heading>
                        <flux:subheading>
                            Daftar notifikasi terbaru terkait kenaikan pangkat dan administrasi.
                        </flux:subheading>
                    </div>
                    <flux:button size="sm" href="{{ route('notifikasi.index') }}" variant="outline">
                        Lihat Semua
                    </flux:button>
                </div>

                <div class="overflow-x-auto">
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Tanggal</flux:table.column>
                            <flux:table.column>Pegawai</flux:table.column>
                            <flux:table.column>Judul</flux:table.column>
                            <flux:table.column>Status</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @forelse($recentNotifications as $notif)
                                <flux:table.row>

                                    <flux:table.cell>
                                        <span class="font-medium">{{ $notif->created_at->format('d M Y') }}</span><br>
                                        <span class="text-xs text-zinc-500">{{ $notif->created_at->format('H:i') }}</span>
                                    </flux:table.cell>

                                    <flux:table.cell>
                                        {{ $notif->employee->name ?? '-' }}
                                    </flux:table.cell>

                                    <flux:table.cell>
                                        {{ $notif->title }}
                                    </flux:table.cell>

                                    <flux:table.cell>
                                        @if($notif->status === 'pending')
                                            <flux:badge color="yellow">Pending</flux:badge>
                                        @elseif($notif->status === 'approved')
                                            <flux:badge color="green">Approved</flux:badge>
                                        @elseif($notif->status === 'rejected')
                                            <flux:badge color="red">Rejected</flux:badge>
                                        @else
                                            <flux:badge>Info</flux:badge>
                                        @endif
                                    </flux:table.cell>

                                </flux:table.row>
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="4" class="text-center py-8 text-zinc-500">
                                        Tidak ada notifikasi terbaru.
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </flux:table.rows>
                    </flux:table>
                </div>
            </flux:card>

        @endif


        {{-- 🟢 ADMIN SAMPAH / DLH → LAPORAN --}}
        @if (auth()->user()->role === 'admin_sampah')

            <flux:card class="relative flex-1 flex flex-col">
                <div class="flex items-center justify-between border-b pb-4 mb-4">
                    <div>
                        <flux:heading size="lg">Laporan Masyarakat Masuk</flux:heading>
                        <flux:subheading>
                            Segera tindak lanjuti laporan yang berstatus pending.
                        </flux:subheading>
                    </div>
                    <flux:button size="sm" href="{{ route('admin.pengaduan.index') }}" variant="outline">
                        Lihat Semua
                    </flux:button>
                </div>

                <div class="overflow-x-auto">
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Tanggal</flux:table.column>
                            <flux:table.column>Pelapor</flux:table.column>
                            <flux:table.column>Deskripsi</flux:table.column>
                            <flux:table.column>Status</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @forelse($recentReports as $report)
                                <flux:table.row>

                                    <flux:table.cell>
                                        <span class="font-medium">{{ $report->created_at->format('d M Y') }}</span><br>
                                        <span class="text-xs text-zinc-500">{{ $report->created_at->format('H:i') }}</span>
                                    </flux:table.cell>

                                    <flux:table.cell>
                                        {{ $report->nama_pelapor }}
                                    </flux:table.cell>

                                    <flux:table.cell>
                                        <p class="truncate max-w-xs text-sm">
                                            {{ $report->deskripsi }}
                                        </p>
                                    </flux:table.cell>

                                    <flux:table.cell>
                                        <flux:badge color="red">Pending</flux:badge>
                                    </flux:table.cell>

                                </flux:table.row>
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="4" class="text-center py-8 text-zinc-500">
                                        Tidak ada laporan pending.
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </flux:table.rows>
                    </flux:table>
                </div>
            </flux:card>

        @endif

    </div>
</x-layouts::app>