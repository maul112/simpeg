<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        $data = [

            // =====================
            // S2
            // =====================
            [
                'level' => 'S2',
                'detail' => 'S2 / MAGISTER PERTANIAN',
                'ids' => [2]
            ],
            [
                'level' => 'S2',
                'detail' => 'S2 / MANAJEMEN',
                'ids' => [7]
            ],
            [
                'level' => 'S2',
                'detail' => 'S2 / MAGISTER MANAJEMEN',
                'ids' => [1,5,6,8,13]
            ],
            [
                'level' => 'S2',
                'detail' => 'S2 / MAGISTER SAINS',
                'ids' => [3]
            ],

            // =====================
            // S1
            // =====================
            [
                'level' => 'S1',
                'detail' => 'S1 / PERTANIAN',
                'ids' => [11]
            ],
            [
                'level' => 'S1',
                'detail' => 'S1 / MANAJEMEN KEUANGAN',
                'ids' => [14]
            ],
            [
                'level' => 'S1',
                'detail' => 'S1 / ADMINISTRASI NIAGA',
                'ids' => [15,30]
            ],
            [
                'level' => 'S1',
                'detail' => 'S1 / MANAJEMEN PEMASARAN',
                'ids' => [16]
            ],
            [
                'level' => 'S1',
                'detail' => 'S1 / ILMU HUKUM',
                'ids' => [10,9,24]
            ],
            [
                'level' => 'S1',
                'detail' => 'S1 / MANAJEMEN',
                'ids' => [18,23,32]
            ],
            [
                'level' => 'S1',
                'detail' => 'S1 / EKONOMI MANAJEMEN',
                'ids' => [17]
            ],
            [
                'level' => 'S1',
                'detail' => 'S1 / TEKNIK',
                'ids' => [12,161,163,164]
            ],
            [
                'level' => 'S1',
                'detail' => 'S1 / EKONOMI',
                'ids' => [20,21,22,160,165]
            ],
            [
                'level' => 'S1',
                'detail' => 'S1 / MANAJEMEN SDM',
                'ids' => [25,26,27,28]
            ],
            [
                'level' => 'S1',
                'detail' => 'S1 / MANAJEMEN EKONOMI',
                'ids' => [19]
            ],
            [
                'level' => 'S1',
                'detail' => 'S1 / THEOLOGI ISLAM',
                'ids' => [29]
            ],
            [
                'level' => 'S1',
                'detail' => 'S1 / AKUNTANSI',
                'ids' => [31]
            ],
            [
                'level' => 'S1',
                'detail' => 'S1 / SAINS',
                'ids' => [162]
            ],

            // =====================
            // SMA
            // =====================
            [
                'level' => 'SMA',
                'detail' => 'PAKET C / SEKRETARIS',
                'ids' => [61,62]
            ],
            [
                'level' => 'SMA',
                'detail' => 'SMK / MEKANIK IOTOMOTIF',
                'ids' => [55]
            ],
            [
                'level' => 'SMA',
                'detail' => 'SMK / MEKANIK UMUM',
                'ids' => [54]
            ],
            [
                'level' => 'SMA',
                'detail' => 'SMEA / PERKANTORAN',
                'ids' => [39,48]
            ],
            [
                'level' => 'SMA',
                'detail' => 'SMU / IPA',
                'ids' => [56,58,91,114]
            ],
            [
                'level' => 'SMA',
                'detail' => 'SMA / ILMU SOSIAL',
                'ids' => [35,37,38,45,51,65,67,85]
            ],
            [
                'level' => 'SMA',
                'detail' => 'SMA / ILMU-ILMU FISIK',
                'ids' => [34,41]
            ],
            [
                'level' => 'SMA',
                'detail' => 'SMA / ILMU BIOLOGI',
                'ids' => [36]
            ],
            [
                'level' => 'SMA',
                'detail' => 'SMA / ILMU FISIK',
                'ids' => [33,99]
            ],
            [
                'level' => 'SMA',
                'detail' => 'SMA / ILMU-ILMU SOSIAL',
                'ids' => [44]
            ],
            [
                'level' => 'SMA',
                'detail' => 'SMU / IPS',
                'ids' => [40,42,43,47,46,63,104]
            ],
            [
                'level' => 'SMA',
                'detail' => 'SMA / IPS',
                'ids' => [57,132]
            ],
            [
                'level' => 'SMA',
                'detail' => 'SMA / IPA',
                'ids' => [133]
            ],
            [
                'level' => 'SMA',
                'detail' => 'SMK / BISNIS MANAJEMEN',
                'ids' => [107,124]
            ],
            [
                'level' => 'SMA',
                'detail' => 'SMA',
                'ids' => [112]
            ],

            // =====================
            // SMP
            // =====================
            [
                'level' => 'SMP',
                'detail' => 'SMP',
                'ids' => [105,127,134,136,148]
            ],
            [
                'level' => 'SMP',
                'detail' => 'PAKET B',
                'ids' => [135,138,139,141,144,146,149,152,154,156,171]
            ],

            // =====================
            // SD
            // =====================
            [
                'level' => 'SD',
                'detail' => 'PAKET A',
                'ids' => [150]
            ],
            [
                'level' => 'SD',
                'detail' => 'SD',
                'ids' => [93,151,153,155,157,158]
            ],

            // =====================
            // PAKET C (MASIF)
            // =====================
            [
                'level' => 'SMA',
                'detail' => 'PAKET C / IPS',
                'ids' => [49,50,52,53,59,60,64,66,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,86,87,88,89,90,92,94,95,96,97,98,100,101,102,103,106,108,109,110,111,113,115,116,117,118,119,120,121,122,123,125,126,128,129,130,131,137,140,142,143,145,147]
            ],
        ];

        foreach ($data as $item) {
            Employee::whereIn('id', $item['ids'])->update([
                'education_level' => $item['level'],
                'education_detail' => $item['detail'],
            ]);
        }
    }
}