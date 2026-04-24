<x-layouts::app :title="__('Pegawai')">
    <div class="p-6 space-y-6">

        <x-managed-message />

        <flux:card>

            {{-- Header & Search Bar --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                <flux:heading size="lg">Data Pegawai</flux:heading>

                <div class="flex w-full sm:w-auto items-center gap-2">

                    {{-- Form Pencarian & Filter --}}
                    <form action="{{ route('pegawai.index') }}" method="GET"
                        class="w-full flex flex-col sm:flex-row gap-2">

                        {{-- Dropdown Filter Golongan --}}
                        <div class="w-full sm:w-48">
                            <flux:select name="rank_grade_id" onchange="this.form.submit()">
                                <option value="">Semua Pangkat/Gol</option>
                                @foreach($rankGrades as $rankGrade)
                                    <option value="{{ $rankGrade->id }}" {{ request('rank_grade_id') == $rankGrade->id ? 'selected' : '' }}>
                                        @if ($rankGrade->rank_name === null)
                                            {{ $rankGrade->grade_code }}
                                        @else
                                            {{ $rankGrade->rank_name . " - " . "(" .  $rankGrade->grade_code . ")" }}
                                        @endif
                                    </option>
                                @endforeach
                            </flux:select>
                        </div>

                        {{-- Input Pencarian --}}
                        <div class="flex w-full sm:w-auto gap-2">
                            <flux:input name="search" type="search" value="{{ request('search') }}"
                                placeholder="Cari Nama atau NIP..." class="w-full sm:w-64"
                                oninput="this.form.submit()" />
                            <flux:button type="submit">Cari</flux:button>
                        </div>

                    </form>

                    <flux:button href="{{ route('pegawai.create') }}" wire:navigate>
                        Tambah Pegawai
                    </flux:button>
                </div>
            </div>

            {{-- Table --}}
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Nama</flux:table.column>
                    <flux:table.column>NIP</flux:table.column>
                    <flux:table.column>Tipe</flux:table.column>
                    <flux:table.column>Gender</flux:table.column>
                    <flux:table.column>TMT</flux:table.column>
                    <flux:table.column class="text-right">Aksi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($employees as $employee)
                        <flux:table.row wire:key="row-{{ $employee->id }}">
                            <flux:table.cell>
                                {{ $employee->name }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $employee->nip }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $employee->type }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $employee->gender == 'l' ? 'L' : 'P' }}
                            </flux:table.cell>

                            <flux:table.cell>
                                @if($employee->type === 'Non ASN')
                                    {{ \Carbon\Carbon::parse($employee->tmt_start)->format('d-m-Y') }} <br>
                                    s/d <br>
                                    {{ $employee->tmt_end ? \Carbon\Carbon::parse($employee->tmt_end)->format('d-m-Y') : 'Sekarang' }}
                                @else
                                    {{ \Carbon\Carbon::parse($employee->tmt_start)->format('d-m-Y') }}
                                @endif
                            </flux:table.cell>

                            <flux:table.cell class="flex justify-start gap-2">
                                <flux:button size="sm" href="{{ route('pegawai.edit', $employee->id) }}" wire:navigate>
                                    Edit
                                </flux:button>

                                <flux:modal.trigger name="delete-employee-{{ $employee->id }}">
                                    <flux:button size="sm" variant="danger" class="cursor-pointer">
                                        Hapus
                                    </flux:button>
                                </flux:modal.trigger>
                                <flux:modal name="delete-employee-{{ $employee->id }}" class="min-w-88">
                                    {{-- Form diarahkan ke fungsi destroy di Controller --}}
                                    <form action="{{ route('pegawai.destroy', $employee->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <div class="space-y-6">
                                            <div>
                                                <flux:heading size="lg">Hapus Data Pegawai?</flux:heading>
                                                <flux:subheading>
                                                    Apakah Anda yakin ingin menghapus <b>{{ $employee->name }}</b>? Tindakan
                                                    ini tidak dapat dibatalkan dan akan menghapus semua data yang terkait.
                                                </flux:subheading>
                                            </div>

                                            <div class="flex justify-end gap-2">
                                                <flux:modal.close>
                                                    <flux:button variant="ghost">Batal</flux:button>
                                                </flux:modal.close>

                                                <flux:button type="submit" variant="danger" class="cursor-pointer">
                                                    Ya, Hapus Data
                                                </flux:button>
                                            </div>
                                        </div>
                                    </form>

                                </flux:modal>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        {{-- Tampilan jika data tidak ditemukan saat pencarian --}}
                        <flux:table.row>
                            <flux:table.cell colspan="6" class="text-center py-4 text-gray-500">
                                Tidak ada data pegawai yang ditemukan.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="mt-4">
                {{ $employees->links() }}
            </div>

        </flux:card>
    </div>
</x-layouts::app>