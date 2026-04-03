<x-layouts::pegawai_app :title="__('Profil')">
    <section class="w-full">
        @include('partials.settings-heading')

        <x-pages::settings.pegawai_layout :heading="__('Autentikasi 2 Faktor')" :subheading="__('Keamanan akun tingkat lanjut')">
            <div class="space-y-6">

                {{-- KONDISI 1: PROSES SETUP --}}
                @if (session('status') == '2fa-enabling' || (auth()->user()->two_factor_secret && !auth()->user()->two_factor_confirmed_at))
                    <div
                        class="p-6 border-2 border-dashed rounded-xl border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-white/5">
                        <flux:heading size="lg">Selesaikan Pengaturan 2FA</flux:heading>
                        <flux:text class="mt-2 mb-4">
                            Pindai QR Code di bawah, lalu masukkan 6 digit kode dari aplikasi untuk konfirmasi.
                        </flux:text>

                        <div class="inline-block p-4 bg-white rounded-lg">
                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        </div>

                        <form method="POST" action="{{ route('pegawai.2fa.confirm') }}" class="mt-6 space-y-4">
                            @csrf
                            <flux:input label="Masukkan Kode Konfirmasi" name="code" placeholder="000000" maxlength="6"
                                required />

                            @error('code')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror

                            <div class="flex gap-2">
                                <flux:button type="submit" variant="primary">Konfirmasi & Aktifkan</flux:button>
                                {{-- Ganti link Batal menjadi form kecil agar secret di hapus dari DB --}}
                            </div>
                        </form>
                        <form method="POST" action="{{ route('pegawai.2fa.disable') }}" class="block mt-2">
                            @csrf
                            @method('DELETE')
                            <flux:button variant="ghost" type="submit">Batal</flux:button>
                        </form>

                        {{-- Recovery codes tetap ditampilkan di bawahnya --}}
                        <div class="mt-8 pt-6 border-t border-zinc-200 dark:border-zinc-800">
                            <flux:heading size="sm">Kode Pemulihan (Recovery Codes)</flux:heading>
                            <div
                                class="grid grid-cols-2 gap-2 mt-2 p-3 font-mono text-xs rounded-lg bg-zinc-200 dark:bg-zinc-800">
                                @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                                    <div>{{ $code }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                {{-- KONDISI 2: SUDAH AKTIF --}}
                @elseif (auth()->user()->hasEnabledTwoFactorAuthentication())
                    <div class="space-y-4">
                        <flux:badge color="green" inset="none">Aktif</flux:badge>
                        <flux:text>2FA sedang melindungi akun Anda. PIN akan diminta setiap kali Anda login.</flux:text>

                        <form method="POST" action="{{ route('pegawai.2fa.disable') }}">
                            @csrf
                            @method('DELETE')
                            <flux:button type="submit" variant="danger" icon="shield-exclamation">
                                Matikan 2FA
                            </flux:button>
                        </form>
                    </div>

                {{-- KONDISI 3: BELUM AKTIF --}}
                @else
                    <div class="space-y-4">
                        <flux:badge color="red">Nonaktif</flux:badge>
                        <flux:text variant="subtle">Tambahkan lapisan keamanan ekstra ke akun Anda menggunakan autentikasi
                            dua faktor.</flux:text>

                        <form method="POST" action="{{ route('pegawai.2fa.enable') }}">
                            @csrf
                            <flux:button type="submit" variant="primary" icon="shield-check">
                                Aktifkan 2FA
                            </flux:button>
                        </form>
                    </div>
                @endif

            </div>
        </x-pages::settings.pegawai_layout>
    </section>
</x-layouts::pegawai_app>