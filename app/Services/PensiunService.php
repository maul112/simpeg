<?php

namespace App\Services;

use App\Models\Employee;
use Carbon\Carbon;

class PensiunService
{
    public function process()
    {
        $now = now()->startOfDay();
        $employees = Employee::whereNotNull('birth_date')
            ->where(function ($q) {
                $q->where('status', '!=', 'nonactive');
            })
            ->get();
        foreach ($employees as $employee) {
            if (!$this->isEligible($employee, $now)) {
                continue;
            }
            $this->retire($employee, $now);
        }
    }

    private function isEligible($employee, $now)
    {
        $birth = Carbon::parse($employee->birth_date)->startOfDay();
        $retirementDate = $birth->copy()->addYears(60);
        return $now->gte($retirementDate);
    }

    private function retire($employee, $date)
    {
        $employee->update([
            'status' => 'nonactive',
        ]);
    }

    private function getPensiunNumber($employee)
    {
        $specialPositionIds = [1, 10];
        if (in_array($employee->position_id, $specialPositionIds)) {
            return 60;
        }
        $positionName = strtolower($employee->position->position_name ?? '');
        if (
            str_contains($positionName, 'sekretaris') ||
            str_contains($positionName, 'kepala dinas lingkungan hidup')
        ) {
            return 60;
        }
        return 58;
    }
}