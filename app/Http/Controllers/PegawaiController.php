<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Cuti;
use App\Models\User;
use App\Support\LeavePolicy;

class PegawaiController extends Controller
{
    public function indexPegawai()
    {
        $pegawais = User::where('role', 'staff')->latest()->get();
        return view('admin.pegawai.index', compact('pegawais'));
    }

    public function createPegawai()
    {
        $departments = User::departmentOptions();
        $positions = User::positionOptions();
        return view('admin.pegawai.create', compact('departments', 'positions'));
    }

    public function storePegawai(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'position' => ['required', 'string', Rule::in(User::positionOptions())],
            'department' => ['required', 'string', Rule::in(User::departmentOptions())],
            'jabatan' => 'nullable|string|max:255',
            'tanggal_masuk' => 'required|date|before_or_equal:today',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $password = $request->filled('password') ? $request->password : Str::random(8);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'position' => $request->position ?: 'Staff',
            'department' => $request->department,
            'jabatan' => $request->jabatan,
            'tanggal_masuk' => $request->tanggal_masuk,
            'password' => Hash::make($password),
            'role' => 'staff',
        ]);

        return redirect()
            ->route('admin.pegawai.index')
            ->with('success', 'Staff berhasil ditambahkan. Password awal: ' . $password);
    }

    public function editPegawai($id)
    {
        $pegawai = User::where('role', 'staff')->findOrFail($id);
        $departments = User::departmentOptions();
        $positions = User::positionOptions();
        return view('admin.pegawai.edit', compact('pegawai', 'departments', 'positions'));
    }

    public function updatePegawai(Request $request, $id)
    {
        $pegawai = User::where('role', 'staff')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'position' => ['required', 'string', Rule::in(User::positionOptions())],
            'department' => ['required', 'string', Rule::in(User::departmentOptions())],
            'jabatan' => 'nullable|string|max:255',
            'tanggal_masuk' => 'required|date|before_or_equal:today',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = $request->only('name', 'email', 'tanggal_lahir', 'jenis_kelamin', 'position', 'department', 'jabatan', 'tanggal_masuk');
        $data['position'] = $data['position'] ?: 'Staff';

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pegawai->update($data);

        return redirect()->route('admin.pegawai.index')->with('success', 'Data staff berhasil diperbarui.');
    }

    public function showPegawai($id)
    {
        $pegawai = User::where('role', 'staff')->findOrFail($id);
        return view('admin.pegawai.show', compact('pegawai'));
    }

    public function destroyPegawai($id)
    {
        $pegawai = User::where('role', 'staff')->findOrFail($id);
        $pegawai->delete();

        return redirect()->route('admin.pegawai.index')->with('success', 'Staff berhasil dihapus.');
    }

    public function dashboard()
    {
        $userId = Auth::id();
        $batasCutiTahunan = 12;

        $totalCuti = Cuti::where('user_id', $userId)->count();
        $cutiPending = Cuti::where('user_id', $userId)->where('status', 'pending')->count();
        $cutiDisetujui = Cuti::where('user_id', $userId)->where('status', 'disetujui')->count();
        $cutiDitolak = Cuti::where('user_id', $userId)->where('status', 'ditolak')->count();
        $cutiTerbaru = Cuti::where('user_id', $userId)->latest()->take(5)->get();

        $leaveSummary = LeavePolicy::summary(Auth::user(), now());
        $batasCutiTahunan = LeavePolicy::MAX_ANNUAL_SLOTS;
        $hakCutiTerkumpul = (int) $leaveSummary['entitlement'];
        $hariAktifTahunIni = (int) $leaveSummary['used_active'];
        $sisaSlotCuti = (int) $leaveSummary['balance'];
        $tanggalMasuk = $leaveSummary['employment_start'];
        $nextSlotDate = $leaveSummary['next_slot_date'];

        return view('pegawai.dashboard', compact(
            'totalCuti',
            'cutiPending',
            'cutiDisetujui',
            'cutiDitolak',
            'cutiTerbaru',
            'batasCutiTahunan',
            'hariAktifTahunIni',
            'sisaSlotCuti',
            'hakCutiTerkumpul',
            'tanggalMasuk',
            'nextSlotDate'
        ));
    }

    public function profile()
    {
        $departments = User::departmentOptions();
        $positions = User::positionOptions();
        return view('pegawai.profile', compact('departments', 'positions'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'password' => 'nullable|string|min:6|confirmed',
        ];

        if ($user->role !== 'staff') {
            $rules['position'] = ['nullable', 'string', Rule::in(User::positionOptions())];
            $rules['department'] = ['nullable', 'string', Rule::in(User::departmentOptions())];
        }

        $request->validate($rules);

        $data = $request->only('name', 'email', 'tanggal_lahir', 'jenis_kelamin');

        if ($user->role !== 'staff') {
            $data['position'] = $request->input('position') ?: $user->role_label;
            $data['department'] = $request->input('department');
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    // Method lama untuk model Pegawai tetap dibiarkan jika masih ada bagian project yang memakainya.
    public function index()
    {
        $pegawais = Pegawai::latest()->get();
        return view('pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_depan' => 'required|string|max:100',
            'nama_belakang' => 'required|string|max:100',
            'email' => 'required|email|unique:pegawais,email',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        Pegawai::create($request->all());
        return redirect()->route('pegawai.index')->with('success', 'Staff berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai)
    {
        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nama_depan' => 'required|string|max:100',
            'nama_belakang' => 'required|string|max:100',
            'email' => 'required|email|unique:pegawais,email,' . $pegawai->id,
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        $pegawai->update($request->all());
        return redirect()->route('pegawai.index')->with('success', 'Data staff berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();
        return redirect()->route('pegawai.index')->with('success', 'Staff berhasil dihapus.');
    }
}
