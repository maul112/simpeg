<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TmtKgbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar pembaruan TMT KGB dikelompokkan berdasarkan tanggal
        $updates = [
            '2025-01-01' => [
                18,  // ALTASYET ALFRET T, SE
                43,  // ABDUL ROSID
                10,  // MACHMUD FAUZI, SH
                93,  // MOH. MASHURI
                98,  // ABDUL HAMED (1977)
                96,  // MAT SULHAN
                47,  // ABDI RAHMAN
                28,  // MOH. SJAIFUL, SE
                46,  // AMINATUZ ZUHRIYAH
                26,  // NURUL HIDAYAT, SE
                154, // SUPRAYITNO	    
                19,  // R. ANA OCTAVIYANI, SE
                87,  // SUGIANTORO
                111, // CHOIRUL ANAM
                31,  // SUDI ENI ,  S.Sos
                13,  // MAULINA NOVIASIH, ST
                14,  // ELOK NUR ISRO'UL H, S.Si
                20,  // RIZAL KAMIL PRAYITNO, SE
                114, // ABDUS SALAM
            ],
            '2025-02-01' => [
                23   // SURAYYAH, SE
            ],
            '2025-04-01' => [
                42,  // MUNIR (1977)
                41,  // BUDIYANTO (1977)
                37,  // MOH. YUSUF
                34,  // MUZAKKI (1968)
                38,  // BUDIONO
                35,  // SUNARDI
                61,  // MOHAMMAD SAFIK
                84,  // MOH. YAMIN
                53,  // ABD HALIM
                54,  // SUBAKKI
                118, // MOHAMMAD ROMADHON
                78,  // SYAFIADI
                98,  // ABDUL HAMED (1977)		
                135, // MAT BERDI
                91,  // ACHMADI
                77,  // ABDURRAHMAN (2007)
                133, // IMAM SANTUSO
                119, // MOH. SAED
                148, // ABUSIRI
                81,  // SUPRAPTO
                152, // SALEHODDIN
                127  // ROMADON
            ],
            '2025-05-01' => [
                30,  // DWI WIDYA ASTUTI, S.Th.I
                129  // JOHAN ROMADON
            ],
            '2025-06-01' => [
                131  // SUPAI
            ],
            '2025-07-01' => [
                21,  // HOIRUS SOLEH, SE
                69,  // HAWI
                126, // ISHAK MULYONO
                85,  // ACH. SAWARI
                128, // SUBUH SUHARDI
                116  // ARIYA TEJA
            ],
            '2025-09-01' => [
                92,  // NAHA
                147, // ROFII
                157, // RACHMAD MULYONO
                103, // ABDULLAH
                156, // KOMAR
                107, // TORIPIN
                108, // AGUS WIDIYANTORO (Di database: AGUS WIDIANTORO)
                149, // MOH. EDI HARTONO
                109  // FATHUR ROSI
            ],
            '2025-10-01' => [
                60,  // SYAMSUL HIDAYAT
                117, // WAHYUDIN
                132, // WALIJAMIIL MUSLIMIN
                33,  // SUKARDI SE (Di database: SUKARDI, SE. Diasumsikan ini, bukan Sukardi Idris
                51   // SUGIARTO
            ],
            '2025-11-01' => [
                25,  // RUSLINA KAMILIYATUN, SH (Di database: RUSLINA KAMILIATUN, SH
                94,  // SUMIRI
                59,  // RESTU MAHARDIKA . A
                58   // HOLIT MAULANA
            ],
            '2025-12-01' => [
                3,   // EKO MARIANTO, S.Sos, M,Si
                6    // ARDIYANDIYAN SYAH, ST,MM (Di database tercatat: ARDIAN SYAH ABDI, ST, MM
            ],
            '2026-01-01' => [
                62,  // AGUS HARIYONO
                56,  // BUDI SANTOSO
                55,  // MASDUKI
                67,  // BAKRI
                72,  // MARUN
                75,  // SYAIFUL
                115, // SANNAN LIKUN
                29,  // ANDRY SETIAWAN, SE
                64,  // IWAN JUNAIDI
                65,  // RONY FETTI
                49,  // ROSIDI
                57,  // KURNIAWATI
                101, // BUNALI
                97,  // ABDU RAHMAN
                122, // MARUKI
                158, // SUPARDI
                159, // PUGUH HERAWAN. AP
                118, // MOHAMMAD ROMADHON
                151, // MOH. NAFIK
                12,  // VENNY SWASTRIANA, SP
                15,  // YUNETA PRASTININGRUM, SE
                16,  // HERI WIJAYA, S.Sos
                17,  // ROBIATUL ADAWIYAH (Di database: ROBIATUL ADAWIJAH, SE)
                11   // LUKMAN (Di database: LUKMAN JAMIL, SH)
            ],
            '2026-03-01' => [
                2,   // LILY ROSLIA, SP,Magr
                7,   // SUBAKKRI, SP, MM (Di database: SUBAKRI, SP, MMP)
                32   // FAUSTINA YUSUF YASSAR,S.Ak
            ],
            '2026-04-01' => [
                24,  // MOH. SUKRIYANTO, SE (Di database: MUHAMMAD SUKRIYANTO,SE)
                39,  // NURUL IFTITAH
                35,  // DIDIK HARYADI
                40,  // NOORDIYANTO HIDAYAT
                140, // SUWANDI
                130, // JUMADI
                144, // HOSEN WIJAYA
                145, // SULASTRI
                86,  // BUDALI
                153, // M. SUTIKMAN
                124, // ASIP
                104, // HAYYAN
                79,  // ABDUL MALIK
                125, // MUHAMMAD SYAIFUL
                142, // SADI
                90,  // SATU'IN
                110, // ABD. HADI
                141, // MOH HASAN (1970)
                150, // MOH. MISKU
                63,  // MAMANG HIDAYAT
                68,  // NURUL HIDAYAT (1969)
                121, // ACHMAD HARIRI
                9,   // SITI FATIMAH, S. SOS, MM
                76   // MOHAMMAD MUSLIM
            ],
            '2026-05-01' => [
                123  // BAMBANG MUSANTO
            ],
            '2026-06-01' => [
                44,  // ACHMAD SUBAIDI
                120, // MOH. DJUSUP (Di database: MOHAMMAD DJUSUP)
                143, // MOHAMMAD TOHIR
                82,  // SUPRATMAN
                83,  // HOIRUL SOLEH
                71,  // NAAM
                105, // MOH AMIN (Di database: MOH. AMIN)
                155  // SUPARLI
            ],
            '2026-07-01' => [
                22,  // KURDI, SE
                50,  // HUZAIRI
                113, // MOKKRA
                112, // NORADI
                52   // MOHAMMAD MESTORY
            ],
            '2026-08-01' => [
                95,  // SUPARDI (1983)
                134  // AMIR MAHMUD
            ],
            '2026-10-01' => [
                88,  // SATUMAN
                139, // SYAMSUL ARIFIN
                73,  // JUMAIN
                66,  // RIDWAN
                146, // MOHAMMAD ROCHMAN
                138, // MOHAMMAD RA'I
                80,  // MOH. SODIK
                27,  // SAHANA, SE
                106, // SOLIHIN
                74,  // BAMBANG PUJI RAHARJO
                136  // NUR ASIA
            ],
            '2026-11-01' => [
                137, // HOIRUL YAROH
                8    // SITI SUGIARTI,S.Sos, MM
            ],
            '2026-12-01' => [
                89   // RIYADI KUSYANTO
            ]
        ];

        // Eksekusi pembaruan
        foreach ($updates as $date => $employeeIds) {
            DB::table('employees')
                ->whereIn('id', $employeeIds)
                ->update(['tmt_kgb' => $date]);
        }
    }
}