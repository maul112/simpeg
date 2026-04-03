<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist aria-label="{{ __('Pengaturan') }}">
            <flux:navlist.item :href="route('pegawai.profil')" wire:navigate >{{ __('Profil') }}</flux:navlist.item>
            <flux:navlist.item :href="route('pegawai.password')" wire:navigate>{{ __('Password') }}</flux:navlist.item>
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <flux:navlist.item :href="route('pegawai.duafaktor')" wire:navigate>{{ __('Authentikasi Dua Faktor') }}</flux:navlist.item>
            @endif
            <flux:navlist.item :href="route('appearance.edit')" wire:navigate>{{ __('Tampilan') }}</flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>
            <p class="text-lg">
                {{ $heading ?? '' }}
            </p>
        </flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
