<div class="p-6 space-y-6">

    <x-managed-message />

    <flux:card>

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <div>
                <flux:heading size="lg">Data Pangkat dan Golongan</flux:heading>
                <flux:subheading>Kelola data pangkat dan golongan</flux:subheading>
            </div>

            {{-- <flux:button wire:click="openCreate">
                Tambah Pangkat
            </flux:button> --}}
        </div>

        {{-- Table --}}
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Nama Golongan</flux:table.column>
                <flux:table.column>Nama Pangkat</flux:table.column>
                <flux:table.column>Jumlah Pegawai</flux:table.column>
                <flux:table.column class="text-right">Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach($ranks as $rank)
                    <flux:table.row wire:key="row-{{ $rank->id }}">
                        <flux:table.cell>
                            {{ $rank->grade_code }}
                        </flux:table.cell>

                        <flux:table.cell>
                            @if ($rank->rank_name === null)
                                <span class="italic text-gray-500">Belum Ditentukan</span>
                            @else
                                {{ $rank->rank_name }}
                            @endif
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $rank->employees_count }}
                        </flux:table.cell>

                        <flux:table.cell class="flex gap-2">

                            <flux:button size="sm" wire:key="edit-btn-{{ $rank->id }}"
                                wire:click="openEdit({{ $rank->id }})" wire:loading.attr="disabled"
                                wire:target="openEdit({{ $rank->id }})" class="cursor-pointer">
                                Edit
                            </flux:button>

                            <flux:button size="sm" variant="danger" wire:key="delete-btn-{{ $rank->id }}"
                                wire:click="delete({{ $rank->id }})" wire:loading.attr="disabled"
                                wire:confirm="Yakin ingin menghapus?" class="cursor-pointer">
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
            {{ $isEdit ? 'Edit Pangkat/Gol' : 'Tambah Pangkat/Gol' }}
        </flux:heading>

        <div class="space-y-4 mt-4">

            <flux:input label="Nama Pangkat" wire:model.defer="rank_name" />

            @error('grade_code')
                <div class="text-red-500 text-sm">
                    {{ $message }}
                </div>
            @enderror

            @error('rank_name')
                <div class="text-red-500 text-sm">
                    {{ $message }}
                </div>
            @enderror

            <flux:input label="Golongan" wire:model.defer="grade_code" />

            <div class="flex justify-end gap-2 mt-6">
                <flux:button variant="ghost" wire:click="closeModal" class="cursor-pointer">
                    Batal
                </flux:button>

                <flux:button wire:click="save" wire:loading.attr="disabled" wire:target="save" class="cursor-pointer bg-emerald-600">
                    {{ $isEdit ? 'Update' : 'Simpan' }}
                </flux:button>
            </div>

        </div>

    </flux:modal>

</div>