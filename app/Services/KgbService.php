<?php

namespace App\Services;

use App\Models\Employee;
use Carbon\Carbon;

class KgbService
{
    public function process()
    {
        $employees = Employee::whereNotNull('tmt_kgb')->get();
        foreach ($employees as $employee) {
            if (!$this->isEligible($employee)) {
                continue;
            }
            $nextDate = $this->getNextKgbDate($employee);
            if (!$nextDate) {
                continue;
            }
            $this->updateKgb($employee, $nextDate);
        }
    }

    private function isEligible($employee)
{
    $targetDate = $this->getNextKgbDate($employee);
    if (!$targetDate) return false;
    
    $now = Carbon::parse(now())->startOfDay();
    
    // deadline = +1 bulan
    $deadline = $targetDate->copy()->addMonth();
    
    // terlalu cepat
    if ($now->lt($targetDate)) {
        return false;
    }
        
    // sudah lewat deadline
    if ($now->gt($deadline)) {
        return false;
    }

    // sudah pernah diproses di siklus ini
    if ($employee->tmt_kgb_updated_at &&
        Carbon::parse($employee->tmt_kgb_updated_at)->gte($targetDate)) {
        return false;
    }

    return true;
}

    private function getNextKgbDate($employee)
    {
        $tmt = Carbon::parse($employee->tmt_kgb)->startOfDay();
        $now = Carbon::parse(now())->startOfDay();

        $yearsElapsed = $tmt->diffInYears($now);
        $cycle = floor($yearsElapsed / 2);

        // ambil siklus saat ini
        return $tmt->copy()->addYears($cycle * 2);
    }

    private function updateKgb($employee, $nextDate)
    {
        $employee->update([
            'tmt_kgb' => $nextDate,
            'tmt_kgb_updated_at' => now(),
        ]);
    }
}