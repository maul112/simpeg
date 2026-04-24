<?php


namespace App\Services;

use App\Models\Employee;
use Carbon\Carbon;

class PromotionService
{
    public function process()
    {
        $employees = Employee::get();
        foreach ($employees as $employee) {
            if (!$this->isEligibleByTime($employee)) {
                continue;
            }
            
            
            $nextRank = $this->getNextRank($employee->rank_grade_id);
            if (!$nextRank) {
                continue;
            }
                
            dd($this->requiresSK($employee, $employee->rank_grade_id, $nextRank));
            // 🚫 butuh SK → skip
            if ($this->requiresSK($employee, $employee->rank_grade_id, $nextRank)) {
                continue;
            }

            // ✅ auto promote
            $this->promote($employee, $nextRank);
        }
    }

    private function isEligibleByTime($employee)
    {
        if (!$employee->tmt_start) return false;

        return Carbon::parse($employee->tmt_start)
            ->addYears(4)
            ->lte(now());
    }

    private function getNextRank($currentId)
    {
        $map = [
            14 => 13, // I/a → I/b
            13 => 12,
            12 => 11,
            11 => 10, // II/a
            10 => 9,
            9 => 8,
            8 => 7,
            7 => 6,   // III/a
            6 => 5,
            5 => 4,
            4 => 3,
            3 => 2,   // IV/a
            2 => 1,   // IV/b
        ];

        return $map[$currentId] ?? null;
    }

    private function requiresSK($employee, $currentId, $nextId)
    {
        $currentGol = $this->getGolongan($currentId);
        $nextGol = $this->getGolongan($nextId);

        // masih dalam golongan sama → aman
        if ($currentGol === $nextGol) {
            return false;
        }

        $level = $employee->education_level;

        // syarat minimal untuk masuk golongan berikutnya
        $minRequirement = match ($nextGol) {
            2 => ['SMA','D1','D2','D3','D4','S1','S2','S3'],
            3 => ['D4','S1','S2','S3'],
            4 => ['S2','S3'],
            default => ['SD','SMP'],
        };

        return !in_array($level, $minRequirement);
    }

    private function promote($employee, $nextRankId)
    {
        $employee->update([
            'rank_grade_id' => $nextRankId,
            'tmt_start' => now(), // reset masa kerja
        ]);
    }

    private function getGolongan($rankId)
    {
        return match (true) {
            $rankId >= 11 => 1, // I
            $rankId >= 7  => 2, // II
            $rankId >= 3  => 3, // III
            default       => 4, // IV
        };
    }
}