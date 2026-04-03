<x-layouts::auth :title="__('Masuk')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Masuk')" :description="__('Masukkan data anda untuk masuk')" />

        @if (session('error'))
            <div class="text-center font-medium text-sm text-red-600">
                {{ session('error') }}
            </div>
        @endif


        <form method="POST" action="{{ route('tamu.masuk') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input name="name" :label="__('Nama')" :value="old('nama')" type="text" required autofocus
                autocomplete="name" :placeholder="__('Full name')" />

            <!-- Address -->
            <flux:input name="address" :label="__('Alamat')" :value="old('address')" type="text" required
                autocomplete="Alamat" placeholder="JL. Raya Soetomo" />

            {{-- <!-- Password -->
            <flux:input name="password" :label="__('Password')" type="password" required autocomplete="new-password"
                :placeholder="__('Password')" viewable />

            <!-- Confirm Password -->
            <flux:input name="password_confirmation" :label="__('Confirm password')" type="password" required
                autocomplete="new-password" :placeholder="__('Confirm password')" viewable /> --}}

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('Masuk') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm">
            <flux:link :href="route('home')" wire:navigate>{{ __('Homepage') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>