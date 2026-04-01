<x-layouts::pegawai_app :title="__('Notifikasi')">
    <flux:heading size="xl" level="1">Notifikasi, {{ $user->employee->name }} ({{ $user->employee->type }})</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">Here's what's new today</flux:text>
    <flux:separator variant="subtle" />
</x-layouts::pegawai_app>