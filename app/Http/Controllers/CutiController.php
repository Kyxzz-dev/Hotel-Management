<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\NewLeaveRequestNotification;
use App\Support\LeavePolicy;
use Throwable;

class CutiController extends Controller
{
    private const BATAS_CUTI_TAHUNAN = 12;

    public function index()
    {
        $cutis = Cuti::with('pegawai')->latest()->get();
        return view('admin.cuti.index', compact('cutis'));
    }

    public function indexPegawai()
    {
        $cutis = Cuti::where('user_id', Auth::id())->latest()->get();
        return view('pegawai.cuti.index', compact('cutis'));
    }

    public function create()
    {
        $leaveSummary = LeavePolicy::summary(Auth::user(), now());

        return view('pegawai.cuti.create', compact('leaveSummary'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_cuti' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jumlah_hari' => 'required|integer|min:1',

            'last_day_of_work' => 'nullable|date',
            'first_day_of_work' => 'nullable|date',
            'person_in_charge' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'alasan' => 'required|string',
        ]);

        $user = Auth::user();
        $userId = $user->id;

        $start = Carbon::parse($request->tanggal_mulai);
        $end = Carbon::parse($request->tanggal_selesai);

        // Hitung durasi dari tanggal, bukan hanya percaya input hidden/readonly.
        $durasi = $start->diffInDays($end) + 1;

        // Logika saldo cuti bulanan:
        // - Pegawai baru belum boleh cuti sebelum genap 1 bulan kerja.
        // - Setelah genap 1 bulan, saldo bertambah 1 hari.
        // - Setiap bulan berikutnya bertambah 1 hari.
        // - Saldo yang tidak dipakai tetap terbawa ke bulan berikutnya.
        // - Pengajuan pending ikut menahan saldo agar tidak double booking.
        $leaveSummary = LeavePolicy::summary($user, now());
        $entitlement = (int) $leaveSummary['entitlement'];
        $balanceBefore = (int) $leaveSummary['balance'];
        $requestDay = $durasi;
        $eligibleAt = $leaveSummary['employment_start']->copy()->addMonthNoOverflow();

        if (now()->startOfDay()->lt($eligibleAt)) {
            return back()->withErrors([
                'tanggal_mulai' => 'Pegawai baru belum bisa mengajukan cuti karena masa kerja belum genap 1 bulan. Hak cuti pertama tersedia pada ' . $eligibleAt->format('d-m-Y') . '.'
            ])->withInput();
        }

        if ($requestDay > $balanceBefore) {
            return back()->withErrors([
                'jumlah_hari' => 'Saldo cuti tidak mencukupi. Saldo saat ini ' . $balanceBefore . ' hari, sedangkan pengajuan membutuhkan ' . $requestDay . ' hari.'
            ])->withInput();
        }

        $balanceAfter = max(0, $balanceBefore - $requestDay);

        $cuti = Cuti::create([
            'user_id' => $userId,

            'jenis_cuti' => $request->jenis_cuti,
            'tanggal_mulai' => $start->format('Y-m-d'),
            'tanggal_selesai' => $end->format('Y-m-d'),
            'jumlah_hari' => $durasi,

            'alasan' => $request->alasan,

            'position' => $user->position ?: $user->role_label,
            'department' => $user->department ?? null,

            'last_day_of_work' => $request->last_day_of_work,
            'first_day_of_work' => $request->first_day_of_work,

            'entitlement' => $entitlement,
            'balance_before' => $balanceBefore,
            'request_day' => $requestDay,
            'balance_after' => $balanceAfter,

            'person_in_charge' => $request->person_in_charge,
            'remarks' => $request->remarks,

            'status' => 'pending',
            'approved_at' => null,
            'rejected_at' => null,
        ]);

        $this->sendNewLeaveRequestEmail($cuti->load('pegawai'));

        return redirect()
            ->route('pegawai.cuti.index')
            ->with('success', 'Pengajuan cuti berhasil dikirim. Saldo cuti sementara tertahan sampai Head Department/GM memproses pengajuan. Saldo bertambah 1 hari setiap genap 1 bulan kerja dan otomatis terbawa jika tidak dipakai.');
    }

    private function sendNewLeaveRequestEmail(Cuti $cuti): void
    {
        try {
            $recipients = $this->getApprovalEmailRecipients($cuti);

            if (empty($recipients)) {
                Log::info('Email notifikasi pengajuan cuti tidak dikirim karena tidak ada penerima GM/Head Department.', [
                    'cuti_id' => $cuti->id,
                    'department' => $cuti->department,
                ]);

                return;
            }

            Mail::to($recipients)->send(new NewLeaveRequestNotification($cuti));
        } catch (Throwable $exception) {
            // Jangan gagalkan pengajuan cuti hanya karena SMTP/email belum terkonfigurasi.
            // Error tetap dicatat di storage/logs/laravel.log agar mudah dicek admin.
            Log::warning('Gagal mengirim email notifikasi pengajuan cuti.', [
                'cuti_id' => $cuti->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function getApprovalEmailRecipients(Cuti $cuti): array
    {
        $department = $cuti->department ?: optional($cuti->pegawai)->department;

        $gmEmails = User::where('role', 'gm')
            ->whereNotNull('email')
            ->pluck('email')
            ->filter()
            ->all();

        $headDepartmentQuery = User::where('role', 'head_department')
            ->whereNotNull('email');

        if ($department) {
            $headDepartmentByDepartment = (clone $headDepartmentQuery)
                ->where('department', $department)
                ->pluck('email')
                ->filter()
                ->all();

            // Jika belum ada Head Department yang departemennya sama, fallback ke semua Head Department
            // agar pengajuan tetap terjangkau oleh approver.
            $headDepartmentEmails = ! empty($headDepartmentByDepartment)
                ? $headDepartmentByDepartment
                : $headDepartmentQuery->pluck('email')->filter()->all();
        } else {
            $headDepartmentEmails = $headDepartmentQuery->pluck('email')->filter()->all();
        }

        return collect(array_merge($gmEmails, $headDepartmentEmails))
            ->unique()
            ->values()
            ->all();
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak'
        ]);

        $cuti = Cuti::findOrFail($id);

        if ($request->status === 'disetujui') {
            $cuti->update([
                'status' => 'disetujui',
                'approved_at' => now(),
                'rejected_at' => null,
                'balance_after' => max(0, $cuti->balance_before - $cuti->request_day),
            ]);

            return back()->with('success', 'Pengajuan cuti berhasil disetujui.');
        }

        if ($request->status === 'ditolak') {
            $cuti->update([
                'status' => 'ditolak',
                'approved_at' => null,
                'rejected_at' => now(),

                // Karena ditolak, balance akhir dikembalikan seperti sebelum request.
                'balance_after' => $cuti->balance_before,
            ]);

            return back()->with('success', 'Pengajuan cuti berhasil ditolak. Slot cuti staff otomatis kembali karena cuti ditolak tidak dihitung.');
        }

        $cuti->update([
            'status' => 'pending',
            'approved_at' => null,
            'rejected_at' => null,
            'balance_after' => max(0, $cuti->balance_before - $cuti->request_day),
        ]);

        return back()->with('success', 'Status cuti berhasil diperbarui.');
    }

    public function approve($id)
    {
        $cuti = Cuti::findOrFail($id);

        if ($cuti->status === 'disetujui') {
            return $this->approvalResponse(false, 'Pengajuan cuti ini sudah pernah disetujui.');
        }

        if ($cuti->status === 'ditolak') {
            return $this->approvalResponse(false, 'Pengajuan cuti yang sudah ditolak tidak bisa langsung disetujui kembali.');
        }

        $cuti->update([
            'status' => 'disetujui',
            'approved_at' => now(),
            'rejected_at' => null,
            'balance_after' => max(0, (int) $cuti->balance_before - (int) $cuti->request_day),
        ]);

        $cuti->refresh();

        return $this->approvalResponse(true, 'Pengajuan cuti berhasil disetujui.', $cuti);
    }

    public function reject($id)
    {
        $cuti = Cuti::findOrFail($id);

        if ($cuti->status === 'ditolak') {
            return $this->approvalResponse(false, 'Pengajuan cuti ini sudah pernah ditolak.');
        }

        if ($cuti->status === 'disetujui') {
            return $this->approvalResponse(false, 'Pengajuan cuti yang sudah disetujui tidak bisa langsung ditolak kembali.');
        }

        $cuti->update([
            'status' => 'ditolak',
            'approved_at' => null,
            'rejected_at' => now(),
            'balance_after' => $cuti->balance_before,
        ]);

        $cuti->refresh();

        return $this->approvalResponse(true, 'Pengajuan cuti berhasil ditolak. Slot cuti staff otomatis kembali karena cuti ditolak tidak dihitung.', $cuti);
    }

    private function approvalResponse(bool $success, string $message, ?Cuti $cuti = null)
    {
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'cuti' => $cuti ? [
                    'id' => $cuti->id,
                    'status' => $cuti->status,
                    'status_label' => ucfirst($cuti->status),
                    'approved_at' => $cuti->approved_at ? Carbon::parse($cuti->approved_at)->format('d M Y H:i') : '-',
                    'rejected_at' => $cuti->rejected_at ? Carbon::parse($cuti->rejected_at)->format('d M Y H:i') : '-',
                    'balance_after' => $cuti->balance_after ?? 0,
                ] : null,
            ], $success ? 200 : 422);
        }

        return back()->with($success ? 'success' : 'error', $message);
    }

    public function laporanCuti()
    {
        $batasCutiTahunan = self::BATAS_CUTI_TAHUNAN;
        $pegawais = $this->getLaporanPegawais();

        return view('admin.cuti.laporan', compact('pegawais', 'batasCutiTahunan'));
    }

    public function exportLaporanCuti(string $format)
    {
        abort_unless(in_array($format, ['csv', 'excel']), 404);

        $batas = self::BATAS_CUTI_TAHUNAN;
        $pegawais = $this->getLaporanPegawais();
        $tahun = now()->year;

        if ($format === 'csv') {
            $filename = "laporan-cuti-detail-{$tahun}.csv";

            return response()->streamDownload(function () use ($pegawais, $batas) {
                $handle = fopen('php://output', 'w');

                // UTF-8 BOM supaya Excel Windows membaca karakter Indonesia dengan benar.
                fwrite($handle, "\xEF\xBB\xBF");

                fputcsv($handle, [
                    'No',
                    'Nama Staff',
                    'Email',
                    'Position',
                    'Department',
                    'Jenis Cuti',
                    'Tanggal Mulai',
                    'Tanggal Selesai',
                    'Jumlah Hari',
                    'Last Day of Work',
                    'First Day of Work',
                    'Entitlement',
                    'Balance Before',
                    'Request',
                    'Balance After',
                    'Reason',
                    'Person In Charge',
                    'Remarks',
                    'Status',
                    'Tanggal Pengajuan',
                    'Approved At',
                    'Rejected At',
                ], ';');

                $no = 1;

                foreach ($pegawais as $pegawai) {
                    foreach ($pegawai->cutis as $cuti) {
                        fputcsv($handle, [
                            $no++,
                            $pegawai->name ?? '-',
                            $pegawai->email ?? '-',
                            $cuti->position ?: ($pegawai->position_label ?? '-'),
                            $cuti->department ?: ($pegawai->department ?? '-'),
                            $cuti->jenis_cuti ?? '-',
                            $cuti->tanggal_mulai ? Carbon::parse($cuti->tanggal_mulai)->format('d-m-Y') : '-',
                            $cuti->tanggal_selesai ? Carbon::parse($cuti->tanggal_selesai)->format('d-m-Y') : '-',
                            $cuti->jumlah_hari ?? $cuti->request_day ?? 0,
                            $cuti->last_day_of_work ? Carbon::parse($cuti->last_day_of_work)->format('d-m-Y') : '-',
                            $cuti->first_day_of_work ? Carbon::parse($cuti->first_day_of_work)->format('d-m-Y') : '-',
                            $cuti->entitlement ?? $batas,
                            $cuti->balance_before ?? 0,
                            $cuti->request_day ?? $cuti->jumlah_hari ?? 0,
                            $cuti->balance_after ?? 0,
                            $cuti->alasan ?? '-',
                            $cuti->person_in_charge ?? '-',
                            $cuti->remarks ?? '-',
                            $cuti->status ?? '-',
                            $cuti->created_at ? Carbon::parse($cuti->created_at)->format('d-m-Y H:i') : '-',
                            $cuti->approved_at ? Carbon::parse($cuti->approved_at)->format('d-m-Y H:i') : '-',
                            $cuti->rejected_at ? Carbon::parse($cuti->rejected_at)->format('d-m-Y H:i') : '-',
                        ], ';');
                    }
                }

                fclose($handle);
            }, $filename, [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
        }

        $filename = "laporan-cuti-detail-{$tahun}.xls";

        $html = view('admin.cuti.export-excel', [
            'pegawais' => $pegawais,
            'batas' => $batas,
            'tahun' => $tahun,
        ])->render();

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }

    private function getLaporanPegawais()
    {
        return User::where('role', 'staff')
            ->with(['cutis' => function ($query) {
                $query->whereYear('tanggal_mulai', now()->year)
                    ->orderBy('tanggal_mulai', 'desc');
            }])
            ->get();
    }

    private function buildLaporanRows($pegawais, int $batas): array
    {
        return $pegawais->map(function ($pegawai) use ($batas) {
            $hariDisetujui = $pegawai->cutis->where('status', 'disetujui')->sum(fn($cuti) => $this->hitungDurasiHari($cuti));
            $hariPending = $pegawai->cutis->where('status', 'pending')->sum(fn($cuti) => $this->hitungDurasiHari($cuti));
            $hariDitolak = $pegawai->cutis->where('status', 'ditolak')->sum(fn($cuti) => $this->hitungDurasiHari($cuti));
            $slotTerpakai = $hariDisetujui + $hariPending;

            return [
                'nama' => $pegawai->name,
                'email' => $pegawai->email,
                'disetujui' => $hariDisetujui,
                'menunggu' => $hariPending,
                'ditolak' => $hariDitolak,
                'sisa_slot' => max(0, $batas - $slotTerpakai),
                'aktif' => $slotTerpakai,
            ];
        })->toArray();
    }

    private function hitungDurasiHari(Cuti $cuti): int
    {
        return Carbon::parse($cuti->tanggal_mulai)->diffInDays(Carbon::parse($cuti->tanggal_selesai)) + 1;
    }
}
