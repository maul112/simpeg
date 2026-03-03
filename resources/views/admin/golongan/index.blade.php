<div class="p-6 space-y-6">

    <flux:card>

        {{-- Header --}}
        <div class="flex justify-between items-center mb-4">
            <flux:heading size="lg">Data Golongan</flux:heading>

            <flux:button wire:click="openCreate">
                Tambah Golongan
            </flux:button>
        </div>

        {{-- Table --}}
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Kode Golongan</flux:table.column>
                <flux:table.column>Jumlah Pegawai</flux:table.column>
                <flux:table.column class="text-right">Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach($grades as $grade)
                    <flux:table.row wire:key="row-{{ $grade->id }}">
                        <flux:table.cell>
                            {{ $grade->grade_code }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $grade->employees_count }}
                        </flux:table.cell>

                        <flux:table.cell class="flex gap-2">

                            <flux:button size="sm" wire:key="edit-btn-{{ $grade->id }}"
                                wire:click="openEdit({{ $grade->id }})" wire:loading.attr="disabled"
                                wire:target="openEdit({{ $grade->id }})">
                                Edit
                            </flux:button>

                            <flux:button size="sm" variant="danger" wire:key="delete-btn-{{ $grade->id }}"
                                wire:click="delete({{ $grade->id }})" wire:loading.attr="disabled"
                                wire:confirm="Yakin ingin menghapus?">
                                Hapus
                            </flux:button>

                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        <div class="mt-4">
            {{ $grades->links() }}
        </div>

    </flux:card>

    {{-- Modal --}}
    <flux:modal wire:model="showModal" class="md:w-1/2">

        <flux:heading size="lg">
            {{ $isEdit ? 'Edit Golongan' : 'Tambah Golongan' }}
        </flux:heading>

        <div class="space-y-4 mt-4">

            <flux:input label="Nama Golongan" wire:model.defer="grade_code" />

            @error('grade_code')
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