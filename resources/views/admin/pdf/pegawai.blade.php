<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            position: relative;
        }

        .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 100px;
        }

        .header h3,
        .header h4,
        .header p {
            margin: 0;
        }

        .header h3, .header h4 {
            font-size: 20px;
        }

        .header p {
            font-size: 14px;
        }

        .line {
            border-top: 2px solid black;
            margin-top: 5px;
        }

        .subline {
            border-top: 1px solid black;
            margin-top: 2px;
        }

        .title {
            text-align: center;
            margin: 15px 0;
            font-weight: bold;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        a {
            font-style: italic;
            font-size: 14px;
        }

        th,
        td {
            padding: 5px;
            text-align: center;
        }

        .left {
            text-align: left;
        }

        .footer {
            width: 100%;
            margin-top: 40px;
        }

        .ttd {
            width: 300px;
            float: right;
            text-align: center;
        }

        .ttd-name {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    {{-- ================= HEADER ================= --}}
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" class="logo">

        <h3>PEMERINTAH KABUPATEN BANGKALAN</h3>
        <h4>DINAS LINGKUNGAN HIDUP</h4>
        <p>Jl. Soekarno Hatta No. 181 Telp (031) 309933 Bangkalan 69116</p>
        <a href="#">Link gatau kemana</a>
        <h4>B A N G K A L A N</h4>
    </div>

    <div class="line"></div>
    <div class="subline"></div>

    {{-- ================= TITLE ================= --}}
    <div class="title">{{ $title }}</div>

    {{-- ================= TABLE ================= --}}
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA / NIP</th>
                <th>GOLONGAN</th>
                <th>JABATAN</th>
                <th>KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $i => $e)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="left">
                        {{ $e->name }} <br>
                        NIP: {{ $e->nip }}
                    </td>
                    <td>
                        {{ $e->rankGrade?->rank_name ?? '-' }} <br>
                        {{ $e->rankGrade?->grade_code ?? '-' }}
                    </td>
                    <td>
                        {{ $e->position?->position_name ?? '-' }}
                    </td>
                    <td>
                        TMT: {{ \Carbon\Carbon::parse($e->tmt_kgb)->format('M Y') }}
                    </td>
                </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;">
                    Tidak ada data
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ================= FOOTER ================= --}}
    <div class="footer">
        <div class="ttd">
            <p>Bangkalan, {{ now()->format('d F Y') }}</p>
            <p><strong>PLT. KEPALA DINAS LINGKUNGAN HIDUP</strong></p>
            <p>KABUPATEN BANGKALAN</p>

            <div class="ttd-name">
                ACHMAD SIDDIK, SAP, MM
            </div>

            <p>Pembina Tk. I</p>
            <p>NIP. 197002052003121004</p>
        </div>
    </div>

</body>

</html>