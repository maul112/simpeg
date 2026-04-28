<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\Notification;
use Illuminate\Support\Str;

class NotificationService
{

    protected PromotionService $promotionService;

    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    // Pemetaan jadwal spesifik untuk masing-masing tipe notifikasi
    protected array $typeSchedules = [
        'pangkat' => [
            ['method' => 'subMonths', 'value' => 8, 'label' => '8 Bulan']
        ],
        'gaji_berkala' => [
            ['method' => 'subMonths', 'value' => 1, 'label' => '1 Bulan']
        ],
        'pensiun' => [
            ['method' => 'subYears', 'value' => 1, 'label' => '1 Tahun']
        ],
    ];

    public function checkAndGenerateNotifications($users)
    {
        $now = Carbon::parse(now())->startOfDay();
        // dd($now . "notif");
        // $debugNotif = [];

        // 1. Kumpulkan semua ID Pegawai yang valid
        $employeeIds = [];
        foreach ($users as $user) {
            if ($user->employee) {
                $employeeIds[] = $user->employee->id;
            }
        }

        // 2. QUERY OPTIMIZATION: Tarik SEMUA notifikasi tahun ini hanya dengan 1 Query
        // Gunakan get() dan pilih kolom yang diperlukan saja agar hemat memori RAM
        $existingNotifications = Notification::whereIn('employee_id', $employeeIds)
            ->get(['employee_id', 'type', 'title']);

        // 3. Susun data ke array multidimensi untuk pencarian kilat (O(1) Lookup)
        // Format: $notifCache[employee_id][type] = ['Judul Notif 1', 'Judul Notif 2']
        $notifCache = [];
        foreach ($existingNotifications as $notif) {
            $notifCache[$notif->employee_id][$notif->type][] = $notif->title;
        }

        foreach ($users as $user) {
            $employee = $user->employee;
            if (!$employee) continue;
            if ($employee->nip == "197002052003121004") {
                continue;
            }

            foreach ($this->typeSchedules as $type => $schedules) {

                $targetDate = $this->calculateTargetDate($employee, $type, $now);

                if (!$targetDate || $now->greaterThanOrEqualTo($targetDate)) {
                    continue;
                }

                foreach ($schedules as $schedule) {

                    if($employee->nip == "197304151998032009") {
                        // dump($schedule);
                        // dump($employee->nip);
                    }
                    
                    $triggerDate = $targetDate->copy()->{$schedule['method']}($schedule['value']);

                    if ($now->greaterThanOrEqualTo($triggerDate)) {
                        
                        // 4. KUNCI GEMBOK (OPTIMIZED): Cek langsung dari array di memori
                        $alreadyNotified = false;
                        $searchKeyword = $targetDate->format('Y-m-d');

                        // Pastikan key array ada sebelum di-loop untuk menghindari Error Undefined Array Key
                        if (isset($notifCache[$employee->id][$type])) {
                            foreach ($notifCache[$employee->id][$type] as $existingTitle) {
                                if (str_contains($existingTitle, $searchKeyword)) {
                                    $alreadyNotified = true;
                                    break;
                                }
                            }
                        }

                        if (!$alreadyNotified) {
                            // $debugNotif[] = [
                            //     'nama' => $employee->name,
                            //     'jenis' => Str::headline($type),
                            //     'kategori_notif' => 'H-' . $schedule['label'],
                            //     'tanggal_target' => $targetDate->format('d M Y'),
                            //     'tanggal_trigger' => $triggerDate->format('d M Y'),
                            // ];

                            $newTitle = 'Peringatan H-' . $schedule['label'] . ' ' . Str::headline($type) . ' (pada ' . $targetDate->format('Y-m-d') . ')';

                            $needsSK = $this->promotionService->needsSK($employee);
                            
                            $existingSK = Notification::where('employee_id', $employee->id)
                                ->where('type', 'pangkat')
                                ->whereIn('status', ['pending','rejected'])
                                ->exists();

                            if ($type === 'pangkat' && $needsSK && $existingSK) {
                                continue;
                            }

                            $status = null;
                            if ($type === 'pangkat' && $needsSK) {
                                $status = 'pending';
                            }

                            Notification::create([
                                'employee_id' => $employee->id,
                                'type'        => $type,
                                'title'       => $newTitle,
                                'message'     => "Sistem mendeteksi jadwal " . Str::headline($type) . " untuk {$employee->name} jatuh pada " . $targetDate->format('d M Y') . ". Mohon segera persiapkan berkas yang dibutuhkan.",
                                'status'      => $status,
                            ]);
                            $notifCache[$employee->id][$type][] = $newTitle;
                        }
                    }
                }
            }
        }
        // dd($debugNotif);
    }

    /**
     * "OTAK" LOGIKA: Menghitung target tanggal berdasarkan aturan masing-masing tipe
     */
    private function calculateTargetDate($employee, string $type, Carbon $now)
    {
        switch ($type) {
            case 'pangkat':
                // Kelipatan 4 Tahun dari TMT
                if (!$employee->tmt_start) return null;
                $tmt = Carbon::parse($employee->tmt_start)->startOfDay();
                $yearsElapsed = $tmt->floatDiffInYears($now);
                
                $nextCycle = ceil($yearsElapsed / 4) * 4;
                if ($nextCycle == 0) $nextCycle = 4;
                return $tmt->copy()->addYears($nextCycle);
                
            case 'gaji_berkala':
                // Kelipatan 2 Tahun dari TMT
                if (!$employee->tmt_kgb) return null;
                $tmt = Carbon::parse($employee->tmt_kgb)->startOfDay();
                $yearsElapsed = $tmt->floatDiffInYears($now);
                
                $nextCycle = ceil($yearsElapsed / 2) * 2;
                if ($nextCycle == 0) $nextCycle = 2;
                return $tmt->copy()->addYears($nextCycle);

            case 'pensiun':
                // Umur 60 Tahun
                if (!$employee->birth_date) return null;
                
                $bday = Carbon::parse($employee->birth_date)->startOfDay();
                $umurPensiun = 60;
                return $bday->copy()->addYears($umurPensiun);

            default:
                return null;
        }
    }
}
