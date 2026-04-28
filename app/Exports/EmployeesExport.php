<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Employee::query();

        // 🔍 APPLY FILTER (penting!)
        // if (!empty($this->filters['search'])) {
        //     $query->where(function ($q) {
        //         $q->where('name', 'like', '%' . $this->filters['search'] . '%')
        //           ->orWhere('nip', 'like', '%' . $this->filters['search'] . '%');
        //     });
        // }

        // dd($this->filters);

        if (!empty($this->filters['rank_grade_id'])) {
            $query->where('rank_grade_id', $this->filters['rank_grade_id']);
        }

        if (!empty($this->filters['education_level'])) {
            $query->where('education_level', $this->filters['education_level']);
        }

        if (!empty($this->filters['gender'])) {
            $query->where('gender', $this->filters['gender']);
        }

        return $query->get()->map(function ($e) {
            return [
                'NAMA' => $e->name,
                'NIP' => "'" . $e->nip,
                'PANGKAT/GOL' => $e->rankGrade->rank_name . ' / ' . $e->rankGrade->grade_code,
                'TMT PANGKAT' => $e->tmt_start,
                "JABATAN" => $e->position->position_name,
                'PENDIDIKAN' => $e->education_detail,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NAMA',
            'NIP',
            'PANGKAT/GOL',
            'TMT PANGKAT',
            "JABATAN",
            'PENDIDIKAN',
        ];
    }
}