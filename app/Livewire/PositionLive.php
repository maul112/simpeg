<?php

namespace App\Livewire;

use App\Models\Position;
use Livewire\Component;
use Livewire\WithPagination;

class PositionLive extends Component
{
    use WithPagination;

    public $positionId;
    public $position_name;
    public $isEdit = false;
    public $showModal = false;

    protected $rules = [
        'position_name' => 'required|string|max:255',
    ];

    public function openCreate()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $this->resetForm();

        $position = Position::findOrFail($id);

        $this->positionId = $position->id;
        $this->position_name = $position->position_name;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            Position::findOrFail($this->positionId)
                ->update([
                    'position_name' => $this->position_name
                ]);
        } else {
            Position::create([
                'position_name' => $this->position_name
            ]);
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        Position::findOrFail($id)->delete();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['positionId', 'position_name', 'isEdit']);
        $this->resetValidation();
    }

    public function render()
    {
        $positions = Position::withCount('employees')
            ->latest()
            ->paginate(10);
        return view('admin.jabatan.index', [
            'positions' => $positions
        ]);
    }
}