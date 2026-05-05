<div class="p-6 space-y-6">

    <x-managed-message />

    <flux:card>

        {{-- Header & Search Bar --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <flux:heading size="lg">Data Jabatan</flux:heading>

            <div class="flex w-full sm:w-auto items-center gap-2">
                {{-- Input Pencarian Real-time Livewire --}}
                <div class="w-full sm:w-64">
                    <flux:input wire:model.live.debounce.300ms="search" type="search"
                        placeholder="Cari Nama Jabatan..." />
                </div>

                <flux:button wire:click="openCreate">
                    Tambah Jabatan
                </flux:button>
            </div>
        </div>

        {{-- Table --}}
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Nama Jabatan</flux:table.column>
                <flux:table.column>Tipe Jabatan</flux:table.column>
                <flux:table.column>Jumlah Pegawai</flux:table.column>
                <flux:table.column class="text-right">Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($positions as $position)
                    <flux:table.row wire:key="row-{{ $position->id }}">
                        <flux:table.cell>
                            {{ $position->position_name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $position->type }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $position->employees_count }}
                        </flux:table.cell>

                        <flux:table.cell class="flex gap-2">

                            <flux:button size="sm" wire:key="edit-btn-{{ $position->id }}"
                                wire:click="openEdit({{ $position->id }})" wire:loading.attr="disabled"
                                wire:target="openEdit({{ $position->id }})">
                                Edit
                            </flux:button>

                            <flux:button size="sm" variant="danger" wire:key="delete-btn-{{ $position->id }}"
                                wire:click="delete({{ $position->id }})" wire:loading.attr="disabled"
                                wire:confirm="Yakin ingin menghapus?">
                                Hapus
                            </flux:button>

                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="3" class="text-center py-4 text-gray-500">
                            @if($search)
                                Tidak menemukan jabatan dengan kata kunci "<b>{{ $search }}</b>".
                            @else
                                Belum ada data jabatan.
                            @endif
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>

        <div class="mt-4">
            {{ $positions->links() }}
        </div>

    </flux:card>

    {{-- Modal --}}
    <flux:modal wire:model="showModal" class="md:w-1/2">

        <flux:heading size="lg">
            {{ $isEdit ? 'Edit Jabatan' : 'Tambah Jabatan' }}
        </flux:heading>

        <div class="space-y-4 mt-4">

            <flux:input label="Nama Jabatan" wire:model.defer="position_name" />

            @error('position_name')
                <div class="text-red-500 text-sm">
                    {{ $message }}
                </div>
            @enderror

            <div class="flex justify-end gap-2 mt-6">
                <flux:button variant="ghost" wire:click="closeModal">
                    Batal
                </flux:button>

                <flux:button wire:click="save" wire:loading.attr="disabled" wire:target="save">
                    {{ $isEdit ? 'Update' : 'Simpan' }}
                </flux:button>
            </div>

        </div>

    </flux:modal>

</div>