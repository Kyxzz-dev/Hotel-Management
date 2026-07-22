<?php

namespace App\Mail;

use App\Models\Cuti;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewLeaveRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Cuti $cuti;
    public string $approvalUrl;

    public function __construct(Cuti $cuti)
    {
        $this->cuti = $cuti->loadMissing('pegawai');
        $this->approvalUrl = route('admin.cuti.index');
    }

    public function build(): self
    {
        $pegawai = $this->cuti->pegawai;
        $nama = $pegawai?->name ?? 'Staff';
        $department = $this->cuti->department ?: ($pegawai?->department ?? '-');

        return $this
            ->subject('Pengajuan Cuti Baru - ' . $nama . ' (' . $department . ')')
            ->view('emails.cuti.new-request')
            ->with([
                'cuti' => $this->cuti,
                'pegawai' => $pegawai,
                'approvalUrl' => $this->approvalUrl,
            ]);
    }
}
