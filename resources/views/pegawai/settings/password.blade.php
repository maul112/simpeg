<x-layouts::pegawai_app :title="__('Password')">
    <section class="w-full">
        @include('partials.settings-heading')
        <x-pages::settings.pegawai_layout :heading="__('Perbarui password')" :subheading="__('Pastikan akun kamu dengan menggunakan password yang kuat')">
            @if(session('status'))
                <flux:subheading class="text-green-600">
                    {{ session('status') }}
                </flux:subheading>
            @endif
            <form method="POST" action="{{ route('profile.password.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('PATCH')
                <flux:input wire:model="current_password" :label="__('Current password')" type="password" required
                    autocomplete="current-password" />
                <flux:input wire:model="password" :label="__('New password')" type="password" required
                    autocomplete="new-password" />
                <flux:input wire:model="password_confirmation" :label="__('Confirm Password')" type="password" required
                    autocomplete="new-password" />

                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <flux:button variant="primary" type="submit" class="w-full" data-test="update-password-button">
                            {{ __('Save') }}
                        </flux:button>
                    </div>
                </div>
            </form>
        </x-pages::settings.pegawai_layout>
    </section>
</x-layouts::pegawai_app>