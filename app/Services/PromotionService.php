<?php


namespace App\Services;

use App\Models\Employee;
use App\Models\Notification;
use Carbon\Carbon;

class PromotionService
{
    public function process()
    {
        $employees = Employee::get();
        foreach ($employees as $employee) {
            // if ($employee->nip == "197002052003121004") {
            //     continue;
            // }
            // dump($employee->name);
            // dump($this->isEligibleByTime($employee));
            if (!$this->isEligibleByTime($employee)) {
                continue;
            }
            $nextRank = $this->getNextRank($employee->rank_grade_id);
            // dump($nextRank);
            if (!$nextRank) {
                continue;
            }

            $nextGol = $this->getGolongan($nextRank);
            // dump($this->canPromote($employee, $nextGol));
            if (!$this->canPromote($employee, $nextGol)) {
                continue;
            }

            // dump($this->requiresSK($employee, $employee->rank_grade_id, $nextRank));
            if ($this->requiresSK($employee, $employee->rank_grade_id, $nextRank)) {
                $targetDate = $this->getTargetDate($employee);
                $targetKey = $targetDate?->format('Y-m-d');
                $latestSK = Notification::where('employee_id', $employee->id)
                    ->where('type', 'pangkat')
                    ->where('title', 'like', "%{$targetKey}%")
                    ->latest()
                    ->first();
                $approved = $latestSK && $latestSK->status === 'approved';
                // dump($approved);
                if (!$approved) {
                    continue;
                }
            }
            // dd($employee->name);

            $this->promote($employee, $nextRank);
        }
    }

    private function isEligibleByTime($employee)
    {
        $targetDate = $this->getTargetDate($employee);

        if (!$targetDate) return false;

        $now = Carbon::parse("2016-04-28")->startOfDay();

        if (!$now->gte($targetDate)) {
            return false;
        }
        if ($employee->last_promoted_at &&
            Carbon::parse($employee->last_promoted_at)->gte($targetDate)) {
            return false;
        }

        return true;
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
        return $currentGol !== $nextGol;
    }

    private function canPromote($employee, $nextGol)
    {
        $level = $employee->education_level;

        $rules = [
            1 => ['SD','SMP'],
            2 => ['SMA','D1','D2','D3','D4','S1','S2','S3'],
            3 => ['D4','S1','S2','S3'],
            4 => ['S2','S3'], // 🔴 ini yang bikin S1 tidak bisa ke IV
        ];

        return in_array($level, $rules[$nextGol] ?? []);
    }

    public function needsSK($employee)
    {
        $nextRank = $this->getNextRank($employee->rank_grade_id);

        if (!$nextRank) return false;

        return $this->requiresSK($employee, $employee->rank_grade_id, $nextRank);
    }

    private function promote($employee, $nextRankId)
    {
        $employee->update([
            'rank_grade_id' => $nextRankId,
            'last_promoted_at' => now(),
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

    public function getTargetDate($employee)
    {
        if (!$employee->tmt_start) return null;

        $tmt = Carbon::parse($employee->tmt_start)->startOfDay();
        $now = Carbon::parse("2016-04-28")->startOfDay();

        $yearsElapsed = $tmt->floatDiffInYears($now);

        $nextCycle = ceil($yearsElapsed / 4) * 4;

        if ($nextCycle == 0) {
            $nextCycle = 4;
        }

        return $tmt->copy()->addYears($nextCycle);
    }
}