<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\LeaveBalanceService;
use Illuminate\Console\Command;

class CheckLeaveBalances extends Command
{
    protected $signature = 'cuti:saldo {--user_id=}';
    protected $description = 'Cek hak cuti, cuti terpakai, dan saldo cuti pegawai.';

    public function handle(LeaveBalanceService $service): int
    {
        $query = User::query();

        if ($this->option('user_id')) {
            $query->where('id', $this->option('user_id'));
        }

        $rows = $query->get()->map(function ($user) use ($service) {
            return [
                'ID' => $user->id,
                'Nama' => $user->name ?? $user->nama ?? '-',
                'Tanggal Masuk' => optional($service->getJoinDate($user))->format('d-m-Y'),
                'Hak Cuti' => $service->earnedLeaveDays($user),
                'Terpakai' => $service->usedLeaveDays($user),
                'Saldo' => $service->balance($user),
            ];
        });

        $this->table(['ID', 'Nama', 'Tanggal Masuk', 'Hak Cuti', 'Terpakai', 'Saldo'], $rows);

        return self::SUCCESS;
    }
}
