<x-layouts::pegawai_app :title="__('Profil')">
    <section class="w-full">
        @include('partials.settings-heading')
        <x-pages::settings.pegawai_layout :heading="__('Profil')" :subheading="__('Perbarui alamat email kamu')">
            @if(session('status'))
                <flux:subheading class="text-green-600">
                    {{ session('status') }}
                </flux:subheading>
            @endif
            <form method="POST" action="{{ route('profile.email.update') }}" class="my-6 w-full space-y-6">
                @csrf
                @method('PATCH')
                <div>
                    <flux:input value="{{ old('email', $user->email) }}" name="email" type="email" required
                        autocomplete="email" />
                    @error('email')
                        <flux:subheading>{{ $message }}</flux:subheading>
                    @enderror
                    <div class="flex items-center gap-4 mt-4">
                        <div class="flex items-center justify-end">
                            <flux:button variant="primary" type="submit" class="w-full"
                                data-test="update-profile-button">
                                {{ __('Save') }}
                            </flux:button>
                        </div>
                    </div>
                </div>
            </form>
        </x-pages::settings.pegawai_layout>
    </section>
</x-layouts::pegawai_app>