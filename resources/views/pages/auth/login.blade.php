<x-layouts::auth :title="__('Log in')">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col items-center justify-center text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo DLH" class="h-32 w-auto object-contain mb-2">
            <h1 class="text-xl font-extrabold tracking-tight text-zinc-900 dark:text-white">
                Dinas Lingkungan Hidup
            </h1>
        </div>

        <x-auth-header :title="__('Masuk ke akun anda')" :description="__('Masukkan email dan password dibawah untuk masuk ke akun pegawai anda')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input name="email" :label="__('Alamat Email')" :value="old('email')" type="email" required autofocus
                autocomplete="email" placeholder="email@contoh.com" />

            <!-- Password -->
            <div class="relative">
                <flux:input name="password" :label="__('Password')" type="password" required
                    autocomplete="current-password" :placeholder="__('Password')" viewable />
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700"
                    data-test="login-button">
                    {{ __('Masuk') }}
                </flux:button>
            </div>
        </form>

        @if (Route::has('home'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse">
                <flux:link :href="route('home')" wire:navigate>{{ __('Homepage') }}</flux:link>
            </div>
        @endif
    </div>
</x-layouts::auth>