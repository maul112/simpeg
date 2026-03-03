<?php

use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Profile settings')] class extends Component {
    use ProfileValidationRules;

    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}; ?>

<x-layouts::pegawai_app :title="__('Pengaturan')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Pengaturan') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Kelola profil anda dan pengaturan akun') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>
    <div class="flex items-start max-md:flex-col">
        <div class="me-10 w-full pb-4 md:w-55">
            <flux:navlist aria-label="{{ __('Settings') }}">
                <flux:navlist.item :href="route('profile.edit')" wire:navigate>{{ __('Profil') }}</flux:navlist.item>
                <flux:navlist.item :href="route('user-password.edit')" wire:navigate>{{ __('Password') }}
                </flux:navlist.item>
                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    <flux:navlist.item :href="route('two-factor.show')" wire:navigate>{{ __('Authentikasi Dua Faktor') }}
                    </flux:navlist.item>
                @endif
                <flux:navlist.item :href="route('appearance.edit')" wire:navigate>{{ __('Tampilan') }}
                </flux:navlist.item>
            </flux:navlist>
        </div>

        <flux:separator class="md:hidden" />

        <div class="flex-1 self-stretch max-md:pt-6">
            <flux:heading>Halo</flux:heading>
            <flux:subheading>Halo</flux:subheading>

            <div class="mt-5 w-full max-w-lg">

                <flux:heading class="sr-only">{{ __('Pengaturan Profil') }}</flux:heading>
                <x-pages::settings.layout :heading="__('Profile')" :subheading="__('Perbarui nama dan alamat email kamu')">
                    <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
                        <flux:input wire:model="name" :label="__('Nama')" type="text" required autofocus
                            autocomplete="name" />

                        <div>
                            <flux:input wire:model="email" :label="__('Email')" type="email" required
                                autocomplete="email" />
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-end">
                                <flux:button variant="primary" type="submit" class="w-full"
                                    data-test="update-profile-button">
                                    {{ __('Save') }}
                                </flux:button>
                            </div>

                            <x-action-message class="me-3" on="profile-updated">
                                {{ __('Saved.') }}
                            </x-action-message>
                        </div>
                    </form>

                    @if ($this->showDeleteUser)
                        <livewire:pages::settings.delete-user-form />
                    @endif
                </x-pages::settings.layout>

            </div>
        </div>
    </div>
</x-layouts::pegawai_app>