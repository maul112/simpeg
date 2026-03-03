<div class="p-6 space-y-6">

    <flux:card>

        {{-- Header --}}
        <div class="flex justify-between items-center mb-4">
            <flux:heading size="lg">Data Pangkat</flux:heading>

            <flux:button wire:click="openCreate">
                Tambah Pangkat
            </flux:button>
        </div>

        {{-- Table --}}
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Nama Pangkat</flux:table.column>
                <flux:table.column>Jumlah Pegawai</flux:table.column>
                <flux:table.column class="text-right">Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach($ranks as $rank)
                    <flux:table.row wire:key="row-{{ $rank->id }}">
                        <flux:table.cell>
                            {{ $rank->rank_name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $rank->employees_count }}
                        </flux:table.cell>

                        <flux:table.cell class="flex gap-2">

                            <flux:button size="sm" wire:key="edit-btn-{{ $rank->id }}"
                                wire:click="openEdit({{ $rank->id }})" wire:loading.attr="disabled"
                                wire:target="openEdit({{ $rank->id }})">
                                Edit
                            </flux:button>

                            <flux:button size="sm" variant="danger" wire:key="delete-btn-{{ $rank->id }}"
                                wire:click="delete({{ $rank->id }})" wire:loading.attr="disabled"
                                wire:confirm="Yakin ingin menghapus?">
                                Hapus
                            </flux:button>

                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        <div class="mt-4">
            {{ $ranks->links() }}
        </div>

    </flux:card>

    {{-- Modal --}}
    <flux:modal wire:model="showModal" class="md:w-1/2">

        <flux:heading size="lg">
            {{ $isEdit ? 'Edit Pangkat' : 'Tambah Pangkat' }}
        </flux:heading>

        <div class="space-y-4 mt-4">

            <flux:input label="Nama Pangkat" wire:model.defer="rank_name" />

            @error('rank_name')
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