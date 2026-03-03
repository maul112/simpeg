<?php

namespace App\Livewire;

use App\Models\Grade;
use Livewire\Component;
use Livewire\WithPagination;

class GradeLive extends Component
{
    use WithPagination;

    public $gradeId;
    public $grade_code;
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

        $grade = Grade::findOrFail($id);

        $this->gradeId = $grade->id;
        $this->grade_code = $grade->position_name;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            Grade::findOrFail($this->gradeId)
                ->update([
                    'position_name' => $this->grade_code
                ]);
        } else {
            Grade::create([
                'grade_code' => $this->grade_code
            ]);
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        Grade::findOrFail($id)->delete();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['gradeId', 'grade_code', 'isEdit']);
        $this->resetValidation();
    }

    public function render()
    {
        $grades = Grade::withCount('employees')
            ->latest()
            ->paginate(10);
        return view('admin.golongan.index', [
            'grades' => $grades
        ]);
    }
}