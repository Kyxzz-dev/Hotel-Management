<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Cuti;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    private array $managementRoles = ['hrd', 'head_department', 'gm'];

    public function dashboard()
    {
        $totalPegawai = User::where('role', 'staff')->count();
        $totalManajemen = User::whereIn('role', $this->managementRoles)->count();
        $totalCuti = Cuti::count();
        $cutiPending = Cuti::where('status', 'pending')->count();
        $cutiDisetujui = Cuti::where('status', 'disetujui')->count();
        $cutiDitolak = Cuti::where('status', 'ditolak')->count();
        $cutiTerbaru = Cuti::with('pegawai')->latest()->take(5)->get();

        if (auth()->user()?->role === 'hrd') {
            $staffBaruBulanIni = User::where('role', 'staff')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            $departemenAktif = User::where('role', 'staff')
                ->whereNotNull('department')
                ->distinct('department')
                ->count('department');

            $staffPerDepartemen = User::where('role', 'staff')
                ->select('department', DB::raw('COUNT(*) as total'))
                ->groupBy('department')
                ->orderByDesc('total')
                ->get();

            return view('admin.hrd-dashboard', compact(
                'totalPegawai',
                'totalCuti',
                'cutiPending',
                'cutiDisetujui',
                'cutiDitolak',
                'cutiTerbaru',
                'staffBaruBulanIni',
                'departemenAktif',
                'staffPerDepartemen'
            ));
        }

        return view('admin.dashboard', compact(
            'totalPegawai',
            'totalManajemen',
            'totalCuti',
            'cutiPending',
            'cutiDisetujui',
            'cutiDitolak',
            'cutiTerbaru'
        ));
    }

    public function index()
    {
        $admins = User::whereIn('role', $this->managementRoles)->get();
        return view('admin.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'role' => 'required|in:hrd,head_department,gm',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.index')->with('success', 'Akun manajemen berhasil ditambahkan.');
    }

    public function edit(User $admin)
    {
        abort_unless(in_array($admin->role, $this->managementRoles), 404);
        return view('admin.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        abort_unless(in_array($admin->role, $this->managementRoles), 404);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'role' => 'required|in:hrd,head_department,gm',
        ]);

        $admin->update($request->only('name', 'email', 'tanggal_lahir', 'jenis_kelamin', 'role'));

        return redirect()->route('admin.index')->with('success', 'Akun manajemen berhasil diperbarui.');
    }

    public function destroy(User $admin)
    {
        abort_unless(in_array($admin->role, $this->managementRoles), 404);
        $admin->delete();
        return redirect()->route('admin.index')->with('success', 'Akun manajemen berhasil dihapus.');
    }
}
