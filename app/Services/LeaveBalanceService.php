<?php

namespace App\Services;

use App\Models\Cuti;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class LeaveBalanceService
{
    /**
     * Status cuti yang dianggap sudah memakai saldo.
     * Sesuaikan dengan status final approved di project Anda jika berbeda.
     */
    private array $approvedStatuses = [
        'disetujui',
        'approved',
        'diterima',
        'selesai',
        'hrd_approved',
        'manager_approved',
    ];

    /**
     * Ambil tanggal mulai kerja pegawai/user.
     * Urutan ini dibuat fleksibel agar cocok dengan nama kolom yang berbeda-beda.
     */
    public function getJoinDate($pegawaiOrUser): Carbon
    {
        $dateFields = [
            'tanggal_masuk',
            'tgl_masuk',
            'mulai_kerja',
            'tanggal_mulai_kerja',
            'join_date',
            'hire_date',
            'created_at',
        ];

        foreach ($dateFields as $field) {
            if (!empty($pegawaiOrUser->{$field})) {
                return Carbon::parse($pegawaiOrUser->{$field})->startOfDay();
            }
        }

        return now()->startOfDay();
    }

    /**
     * Hak cuti yang sudah matang.
     * Contoh:
     * - belum 1 bulan = 0
     * - genap 1 bulan = 1
     * - genap 2 bulan = 2
     */
    public function earnedLeaveDays($pegawaiOrUser, ?Carbon $asOf = null): int
    {
        $asOf = ($asOf ?: now())->copy()->startOfDay();
        $joinDate = $this->getJoinDate($pegawaiOrUser);

        if ($asOf->lt($joinDate->copy()->addMonthNoOverflow())) {
            return 0;
        }

        return max(0, $joinDate->diffInMonths($asOf));
    }

    /**
     * Total cuti yang sudah disetujui dan mengurangi saldo.
     */
    public function usedLeaveDays($pegawaiOrUser): int
    {
        $query = Cuti::query();

        if (Schema::hasColumn('cutis', 'user_id') && !empty($pegawaiOrUser->id)) {
            $query->where('user_id', $pegawaiOrUser->id);
        } elseif (Schema::hasColumn('cutis', 'pegawai_id') && !empty($pegawaiOrUser->id)) {
            $query->where('pegawai_id', $pegawaiOrUser->id);
        }

        if (Schema::hasColumn('cutis', 'status')) {
            $query->whereIn('status', $this->approvedStatuses);
        }

        if (Schema::hasColumn('cutis', 'jumlah_hari')) {
            return (int) $query->sum('jumlah_hari');
        }

        $startColumn = $this->firstExistingColumn('cutis', ['tanggal_mulai', 'tgl_mulai', 'mulai_cuti', 'start_date']);
        $endColumn = $this->firstExistingColumn('cutis', ['tanggal_selesai', 'tgl_selesai', 'selesai_cuti', 'end_date']);

        if (!$startColumn || !$endColumn) {
            return 0;
        }

        return (int) $query->get()->sum(function ($cuti) use ($startColumn, $endColumn) {
            if (empty($cuti->{$startColumn}) || empty($cuti->{$endColumn})) {
                return 0;
            }

            return Carbon::parse($cuti->{$startColumn})
                ->startOfDay()
                ->diffInDays(Carbon::parse($cuti->{$endColumn})->startOfDay()) + 1;
        });
    }

    /**
     * Saldo cuti = hak cuti terkumpul - cuti yang sudah dipakai.
     */
    public function balance($pegawaiOrUser, ?Carbon $asOf = null): int
    {
        return max(0, $this->earnedLeaveDays($pegawaiOrUser, $asOf) - $this->usedLeaveDays($pegawaiOrUser));
    }

    /**
     * Validasi apakah user boleh mengajukan cuti.
     */
    public function canRequest($pegawaiOrUser, int $requestedDays, ?Carbon $asOf = null): array
    {
        $joinDate = $this->getJoinDate($pegawaiOrUser);
        $eligibleAt = $joinDate->copy()->addMonthNoOverflow()->startOfDay();
        $today = ($asOf ?: now())->copy()->startOfDay();
        $balance = $this->balance($pegawaiOrUser, $today);

        if ($today->lt($eligibleAt)) {
            return [
                'allowed' => false,
                'message' => 'Pegawai baru belum bisa mengajukan cuti karena masa kerja belum genap 1 bulan. Hak cuti pertama tersedia pada ' . $eligibleAt->format('d-m-Y') . '.',
                'balance' => 0,
                'eligible_at' => $eligibleAt,
            ];
        }

        if ($requestedDays > $balance) {
            return [
                'allowed' => false,
                'message' => 'Saldo cuti tidak mencukupi. Saldo saat ini ' . $balance . ' hari, sedangkan pengajuan membutuhkan ' . $requestedDays . ' hari.',
                'balance' => $balance,
                'eligible_at' => $eligibleAt,
            ];
        }

        return [
            'allowed' => true,
            'message' => 'Pengajuan cuti valid.',
            'balance' => $balance,
            'eligible_at' => $eligibleAt,
        ];
    }

    private function firstExistingColumn(string $table, array $columns): ?string
    {
        foreach ($columns as $column) {
            if (Schema::hasColumn($table, $column)) {
                return $column;
            }
        }

        return null;
    }
}
