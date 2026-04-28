<?php

namespace App\Http\Controllers;

use App\Exports\EmployeesExport;
use App\Models\Employee;
use App\Models\Position;
use App\Models\RankGrade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $rank_grade_id = $request->input('rank_grade_id');
        $rankGrades = RankGrade::orderBy('created_at', 'asc')->get();
        $employees = Employee::query()
            ->with(['rankGrade'])
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->when($rank_grade_id, function ($query, $rank_grade_id) {
                // Filter berdasarkan rank_grade_id jika user memilih pangkat
                return $query->where('rank_grade_id', $rank_grade_id);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
        return view('admin.pegawai.index', compact('employees', 'search', 'rank_grade_id', 'rankGrades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rank_grades = RankGrade::all();
        $positions = Position::all();
        return view('admin.pegawai.create', compact('rank_grades', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Definisikan Aturan Validasi
        $rules = [
            // Validasi Akun (Users)
            // 'email'         => 'required|email|unique:users,email',
            // 'password'      => 'required|min:8',

            // Validasi Biodata (Employees)
            'nip'           => 'required|string|digits:18|unique:employees,nip',
            'name'          => 'required|string|max:255',
            'birth_date'    => 'required|date',
            'gender'        => 'required|in:l,p',
            'status'        => 'required|string',
            'education_level' => 'required|in:SD,SMP,SMA,D1,D2,D3,D4,S1,S2,S3',
            'education_detail' => 'nullable|string|max:255',
            'tmt_start'     => 'required|date',
            'tmt_end'       => 'nullable|date|after_or_equal:tmt_start',
            'tmt_kgb'       => 'required|date|after_or_equal:tmt_start',
            'type'          => 'required|in:ASN,Non ASN',
            'position_id'   => 'nullable|exists:positions,id',
            'rank_grade_id' => 'nullable|exists:rank_grades,id',
        ];

        // 2. Definisikan Pesan Bahasa Indonesia
        $messages = [
            'required'        => 'Kolom :attribute wajib diisi.',
            'unique'          => ':attribute ini sudah terdaftar di sistem.',
            'digits'          => ':attribute harus berupa angka dan tepat :digits digit.',
            'date'            => 'Format :attribute harus berupa tanggal yang valid.',
            'after_or_equal'  => 'Tanggal :attribute harus sama dengan atau setelah TMT Start.',
            'in'              => 'Pilihan :attribute tidak valid.',
            'exists'          => 'Data :attribute yang dipilih tidak ditemukan.',
        ];

        // 3. Ubah nama atribut agar lebih enak dibaca user (Opsional)
        $attributes = [
            'name'          => 'Nama Lengkap',
            'birth_date'    => 'Tanggal Lahir',
            'gender'        => 'Jenis Kelamin',
            'status'        => 'Status Pegawai',
            'education_level' => 'Pendidikan Terakhir',
            'education_detail' => 'Detail Pendidikan',
            'tmt_start'     => 'TMT Awal',
            'tmt_end'       => 'TMT Akhir',
            'tmt_kgb'       => 'TMT KGB',
            'type'          => 'Tipe Pegawai',
            'position_id'   => 'Jabatan',
            'rank_grade_id' => 'Pangkat/Gol',
        ];

        // 4. Eksekusi Validasi
        $validatedData = $request->validate($rules, $messages, $attributes);

        // 5. Simpan Data (Gunakan DB Transaction karena ada 2 tabel)
        DB::transaction(function () use ($validatedData) {
            $employee = Employee::create([
                'nip'           => $validatedData['nip'],
                'name'          => $validatedData['name'],
                'birth_date'    => $validatedData['birth_date'],
                'gender'        => $validatedData['gender'],
                'status'        => $validatedData['status'],
                'education_level' => $validatedData['education_level'],
                'education_detail' => $validatedData['education_detail'],
                'tmt_start'     => $validatedData['tmt_start'],
                'tmt_end'       => $validatedData['tmt_end'] ?? null,
                'tmt_kgb'       => $validatedData['tmt_kgb'],
                'type'          => $validatedData['type'],
                'position_id'   => $validatedData['position_id'] ?? null,
                'rank_grade_id' => $validatedData['rank_grade_id'] ?? null,
            ]);

            User::create([
                'employee_id'   => $employee->id,
                'email'         => "{$employee->nip}@email.com",
                'password'      => bcrypt($employee->nip),
            ]);
        });

        return redirect()->route('pegawai.index')->with('success', 'Data Pegawai dan Akun berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $pegawai)
    {
        return view('pegawai.show', compact('pegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $pegawai)
    {
        $rank_grades = RankGrade::all();
        $positions = Position::all();
        // dd($pegawai);
        return view('admin.pegawai.edit', compact('pegawai', 'rank_grades', 'positions'));
    }
        
        /**
         * Update the specified resource in storage.
        */
    public function update(Request $request, Employee $pegawai)
    {
        $rules = [
            // 'email'         => 'required|email|unique:users,email,' . ($user ? $user->id : ''),
            // 'password'      => 'nullable|min:8',
            'nip'           => 'required|string|digits:18|unique:employees,nip,' . $pegawai->id,
            'name'          => 'required|string|max:255',
            'birth_date'    => 'required|date',
            'gender'        => 'required|in:l,p',
            'status'        => 'required|string',
            'education_level' => 'required|in:SD,SMP,SMA,D1,D2,D3,D4,S1,S2,S3',
            'education_detail' => 'required|string',
            'tmt_start'     => 'required|date',
            'tmt_end'       => 'nullable|date|after_or_equal:tmt_start',
            'tmt_kgb'       => 'required|date|after_or_equal:tmt_start',
            'type'          => 'required|string',
            'position_id'   => 'nullable|exists:positions,id',
            'rank_grade_id' => 'nullable|exists:rank_grades,id',
        ];

        $messages = [
            'required'        => 'Kolom :attribute wajib diisi.',
            'unique'          => ':attribute ini sudah dipakai orang lain.',
            'digits'          => ':attribute harus berupa angka dan tepat :digits digit.',
            'date'            => 'Format :attribute harus berupa tanggal yang valid.',
            'after_or_equal'  => 'Tanggal :attribute harus sama dengan atau setelah TMT Awal.',
            'in'              => 'Pilihan pada kolom :attribute tidak valid.',
            'exists'          => 'Data :attribute yang dipilih tidak ditemukan.',
        ];

        $attributes = [
            'nip'           => 'NIP',
            'name'          => 'Nama',
            'birth_date'    => 'Tanggal Lahir',
            'gender'        => 'Jenis Kelamin',
            'tmt_start'     => 'TMT Awal',
            'tmt_end'       => 'TMT Akhir',
            'tmt_kgb'       => 'TMT Kenaikan Gaji Berkala',
            'type'          => 'Tipe Pegawai',
            'position_id'   => 'Jabatan',
            'rank_grade_id' => 'Pangkat/Gol',
        ];

        $validatedData = $request->validate($rules, $messages, $attributes);

        DB::transaction(function () use ($validatedData, $pegawai) {
            // 1. Update Biodata Pegawai
            $pegawai->update([
                'nip'           => $validatedData['nip'],
                'name'          => $validatedData['name'],
                'birth_date'    => $validatedData['birth_date'],
                'gender'        => $validatedData['gender'],
                'status'        => $validatedData['status'],
                'education_level' => $validatedData['education_level'],
                'education_detail' => $validatedData['education_detail'],
                'tmt_start'     => $validatedData['tmt_start'],
                'tmt_end'       => $validatedData['tmt_end'] ?? null,
                'tmt_kgb'       => $validatedData['tmt_kgb'],
                'type'          => $validatedData['type'],
                'position_id'   => $validatedData['position_id'] ?? null,
                'rank_grade_id' => $validatedData['rank_grade_id'] ?? null,
            ]);
        });

        return redirect()->route('pegawai.index')->with('success', 'Data Pegawai berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $pegawai)
    {
        // dd($pegawai);
        $pegawai->delete();

        return redirect()->route('pegawai.index')
                         ->with('success', 'Data pegawai berhasil dihapus.');
    }

    public function export(Request $request) {
        return Excel::download(
            new EmployeesExport($request->only(['education_level', 'gender', 'rank_grade_id'])), 'pegawai.xlsx'
        );
    }
}
