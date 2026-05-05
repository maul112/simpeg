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
            $nextRank = $this->getNextRank($employee->rank_grade_id);
            $nextGol = $this->getGolongan($nextRank);
            $targetDate = $this->getTargetDate($employee);
            // if ($employee->nip == "197002052003121005") {
            //     dump($this->isEligibleByTime($employee));
            //     dump($nextRank);
            //     dump($employee->education_level);
            //     dump($this->canPromote($employee, $nextGol));
            //     dump($employee->name);
            // }

            if (!$this->isEligibleByTime($employee)) {
                continue;
            }
            
            if (!$nextRank) {
                continue;
            }

            if (!$this->canPromote($employee, $nextGol)) {
                continue;
            }

            $latestSK = Notification::where('employee_id', $employee->id)
                ->where('type', 'pangkat')
                ->where('title', 'like', '%' . $targetDate->format('Y-m-d') . '%')
                ->latest()
                ->first();

            // if ($employee->nip == "197002052003121005") {
                // dd($approved);
            // }

            if ($latestSK !== null && $latestSK->status !== 'approved') {
                $this->moveCycle($employee, $targetDate);
                continue;
            }

            $this->promote($employee, $nextRank, $targetDate);
        }
    }

    private function moveCycle($employee, $targetDate)
    {
        $employee->update([
            'tmt_start' => $targetDate
        ]);
    }

    public function isEligibleByTime($employee)
    {
        $targetDate = $this->getTargetDate($employee);
        if (!$targetDate) return false;

        $now = now()->startOfDay();
        $deadline = $targetDate->copy()->addMonth();

        // belum waktunya
        if ($now->lt($targetDate)) return false;

        // lewat deadline → hangus
        if ($now->gt($deadline)) return false;

        return true;
    }

    public function getNextRank($currentId)
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
        return true;
    }

    public function canPromote($employee, $nextGol)
    {
        $level = $employee->education_level;

        $rules = [
            1 => ['SD','SMP'],
            2 => ['SMA','D1','D2','D3','D4','S1','S2','S3'],
            3 => ['D4','S1','S2','S3'],
            4 => ['S2','S3'],
        ];

        return in_array($level, $rules[$nextGol] ?? []);
    }

    public function needsSK($employee)
    {
        $nextRank = $this->getNextRank($employee->rank_grade_id);

        if (!$nextRank) return false;

        return $this->requiresSK($employee, $employee->rank_grade_id, $nextRank);
    }

    private function promote($employee, $nextRankId, $targetDate)
    {
        $employee->update([
            'rank_grade_id' => $nextRankId,
            'tmt_start'     => $targetDate,
        ]);
    }

    public function getGolongan($rankId)
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

        $interval = $this->getInterval($employee);

        $tmt = Carbon::parse($employee->tmt_start)->startOfDay();
        $now = now()->startOfDay();

        $yearsElapsed = $tmt->diffInYears($now);

        $cycle = floor($yearsElapsed / $interval);

        return $tmt->copy()->addYears($cycle * $interval);
    }

    private function getInterval($employee)
    {
        return $employee->position->type === 'fungsional' ? 3 : 4;
    }
}