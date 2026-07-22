<?php

namespace App\Support;

use App\Models\Cuti;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeavePolicy
{
    public const MAX_ANNUAL_SLOTS = 12;

    public static function employmentStart(User $user): Carbon
    {
        $start = $user->tanggal_masuk ?: $user->created_at;

        return $start
            ? Carbon::parse($start)->startOfDay()
            : now()->startOfDay();
    }

    /**
     * Slot cuti bertambah 1 setiap 1 bulan kerja penuh.
     * Saldo tidak hangus per bulan: jika bulan ini tidak dipakai,
     * maka saldo otomatis terbawa dan bertambah pada bulan berikutnya.
     * Maksimum akumulasi dibatasi 12 hari agar sesuai batas cuti tahunan.
     */
    public static function accruedEntitlement(User $user, ?Carbon $asOf = null): int
    {
        $asOf = ($asOf ?: now())->copy()->startOfDay();
        $start = self::employmentStart($user);

        if ($asOf->lt($start->copy()->addMonth())) {
            return 0;
        }

        return min(self::MAX_ANNUAL_SLOTS, max(0, $start->diffInMonths($asOf)));
    }

    public static function usedActiveDays(User $user): int
    {
        return (int) Cuti::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'disetujui'])
            ->sum(DB::raw('COALESCE(request_day, jumlah_hari, DATEDIFF(tanggal_selesai, tanggal_mulai) + 1)'));
    }

    public static function usedApprovedDays(User $user): int
    {
        return (int) Cuti::where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->sum(DB::raw('COALESCE(request_day, jumlah_hari, DATEDIFF(tanggal_selesai, tanggal_mulai) + 1)'));
    }

    public static function summary(User $user, ?Carbon $asOf = null): array
    {
        $entitlement = self::accruedEntitlement($user, $asOf);
        $activeUsed = self::usedActiveDays($user);
        $approvedUsed = self::usedApprovedDays($user);

        return [
            'entitlement' => $entitlement,
            'used_active' => $activeUsed,
            'used_approved' => $approvedUsed,
            'balance' => max(0, $entitlement - $activeUsed),
            'employment_start' => self::employmentStart($user),
            'next_slot_date' => self::nextSlotDate($user, $asOf),
        ];
    }

    public static function nextSlotDate(User $user, ?Carbon $asOf = null): ?Carbon
    {
        $asOf = ($asOf ?: now())->copy()->startOfDay();
        $start = self::employmentStart($user);
        $accrued = self::accruedEntitlement($user, $asOf);

        if ($accrued >= self::MAX_ANNUAL_SLOTS) {
            return null;
        }

        return $start->copy()->addMonths($accrued + 1);
    }
}
