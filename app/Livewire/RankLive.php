<?php

namespace App\Livewire;

use App\Models\Position;
use App\Models\Rank;
use Livewire\Component;
use Livewire\WithPagination;

class RankLive extends Component
{
    use WithPagination;

    public $rankId;
    public $rank_name;
    public $isEdit = false;
    public $showModal = false;

    protected $rules = [
        'rank_name' => 'required|string|max:255',
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

        $rank = Rank::findOrFail($id);

        $this->rankId = $rank->id;
        $this->rank_name = $rank->rank_name;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            Position::findOrFail($this->rankId)
                ->update([
                    'rank_name' => $this->rank_name
                ]);
        } else {
            Position::create([
                'rank_name' => $this->rank_name
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
        $this->reset(['rankId', 'rank_name', 'isEdit']);
        $this->resetValidation();
    }

    public function render()
    {
        $ranks = Rank::withCount('employees')
            ->latest()
            ->paginate(10);
        return view('admin.pangkat.index', [
            'ranks' => $ranks
        ]);
    }
}