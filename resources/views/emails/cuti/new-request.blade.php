@php
    use Carbon\Carbon;

    $nama = $pegawai?->name ?? 'Staff';
    $email = $pegawai?->email ?? '-';
    $position = $cuti->position ?: ($pegawai?->position_label ?? '-');
    $department = $cuti->department ?: ($pegawai?->department ?? '-');
    $jabatan = $pegawai?->jabatan ?? $pegawai?->position_detail ?? '-';

    $tanggalMulai = $cuti->tanggal_mulai ? Carbon::parse($cuti->tanggal_mulai)->format('d M Y') : '-';
    $tanggalSelesai = $cuti->tanggal_selesai ? Carbon::parse($cuti->tanggal_selesai)->format('d M Y') : '-';

    $jumlahHari = $cuti->jumlah_hari ?? $cuti->request_day ?? '-';
    $balanceBefore = $cuti->balance_before ?? '-';
    $requestDay = $cuti->request_day ?? $cuti->jumlah_hari ?? '-';
    $balanceAfter = $cuti->balance_after ?? '-';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Cuti Baru</title>
</head>

<body style="margin:0; padding:0; background:#f7f2ef; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f7f2ef; margin:0; padding:32px 12px;">
        <tr>
            <td align="center">

                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px; background:#ffffff; border-radius:20px; overflow:hidden; border:1px solid #eadfd9; box-shadow:0 16px 42px rgba(92,20,34,0.16);">

                    <tr>
                        <td style="background:linear-gradient(135deg,#4b0f1d,#7a1d35,#b8893b); padding:30px 34px; color:#ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                        <div style="font-size:12px; letter-spacing:1.6px; text-transform:uppercase; color:#f8d69a; font-weight:700;">
                                            Hotel Leave Management
                                        </div>

                                        <h1 style="font-size:25px; line-height:1.3; margin:10px 0 0; color:#ffffff;">
                                            Pengajuan Cuti Baru Menunggu Approval
                                        </h1>

                                        <p style="font-size:14px; line-height:1.7; margin:12px 0 0; color:#fbeedc;">
                                            Mohon cek pengajuan cuti staff berikut melalui panel approval.
                                        </p>
                                    </td>

                                    <td align="right" width="72">
                                        <div style="width:58px; height:58px; border-radius:18px; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.28); text-align:center; line-height:58px; font-size:27px;">
                                            🏨
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px 34px 26px;">

                            <div style="background:#fff8ef; border:1px solid #ead8c2; border-radius:16px; padding:16px 18px; margin-bottom:24px;">
                                <div style="font-size:13px; color:#7a2235; font-weight:700; margin-bottom:5px;">
                                    Informasi Pengajuan
                                </div>
                                <div style="font-size:13px; color:#6b7280; line-height:1.6;">
                                    Pengajuan cuti baru telah dikirim oleh staff dan membutuhkan validasi dari Head Department / General Manager.
                                </div>
                            </div>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
                                <tr>
                                    <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; color:#7c8794; width:38%; font-size:13px;">
                                        Nama Staff
                                    </td>
                                    <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; font-weight:700; font-size:14px;">
                                        {{ $nama }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; color:#7c8794; font-size:13px;">
                                        Email
                                    </td>
                                    <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; font-size:14px;">
                                        <a href="mailto:{{ $email }}" style="color:#8c1d35; text-decoration:none; font-weight:700;">
                                            {{ $email }}
                                        </a>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; color:#7c8794; font-size:13px;">
                                        Role / Posisi
                                    </td>
                                    <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; font-size:14px;">
                                        <span style="display:inline-block; background:#f3e3dc; color:#7a2235; padding:5px 10px; border-radius:999px; font-size:12px; font-weight:700;">
                                            {{ $position }}
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; color:#7c8794; font-size:13px;">
                                        Departemen
                                    </td>
                                    <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; font-size:14px;">
                                        {{ $department }}
                                    </td>
                                </tr>

                                @if(($position === 'Staff' || strtolower($position) === 'staff') && $jabatan !== '-')
                                    <tr>
                                        <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; color:#7c8794; font-size:13px;">
                                            Jabatan
                                        </td>
                                        <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; font-size:14px;">
                                            {{ $jabatan }}
                                        </td>
                                    </tr>
                                @endif

                                <tr>
                                    <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; color:#7c8794; font-size:13px;">
                                        Jenis Cuti
                                    </td>
                                    <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; font-size:14px;">
                                        <span style="display:inline-block; background:#f8e8d1; color:#7a2235; padding:5px 11px; border-radius:999px; font-size:12px; font-weight:700;">
                                            {{ $cuti->jenis_cuti ?? '-' }}
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; color:#7c8794; font-size:13px;">
                                        Tanggal Cuti
                                    </td>
                                    <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; font-size:14px; font-weight:700;">
                                        {{ $tanggalMulai }} - {{ $tanggalSelesai }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; color:#7c8794; font-size:13px;">
                                        Jumlah Hari
                                    </td>
                                    <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; font-size:14px;">
                                        {{ $jumlahHari }} hari
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; color:#7c8794; font-size:13px;">
                                        PIC Selama Cuti
                                    </td>
                                    <td style="padding:12px 0; border-bottom:1px solid #f0e4dc; font-size:14px;">
                                        {{ $cuti->person_in_charge ?: '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:12px 0; color:#7c8794; vertical-align:top; font-size:13px;">
                                        Alasan
                                    </td>
                                    <td style="padding:12px 0; line-height:1.6; font-size:14px;">
                                        {{ $cuti->alasan ?: '-' }}
                                    </td>
                                </tr>
                            </table>

                            <div style="margin-top:26px; padding:18px; border-radius:16px; background:#fbfaf8; border:1px solid #eadfd9;">
                                <div style="font-weight:700; margin-bottom:12px; color:#111827; font-size:14px;">
                                    Sisa Cuti
                                </div>

                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td style="font-size:13px; color:#6b7280; padding:6px 0;">
                                            Balance Before
                                        </td>
                                        <td align="right" style="font-size:13px; font-weight:700; padding:6px 0;">
                                            {{ $balanceBefore }} hari
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="font-size:13px; color:#6b7280; padding:6px 0;">
                                            Request
                                        </td>
                                        <td align="right" style="font-size:13px; font-weight:700; padding:6px 0; color:#8c1d35;">
                                            {{ $requestDay }} hari
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="font-size:13px; color:#6b7280; padding:6px 0;">
                                            Balance After jika disetujui / pending
                                        </td>
                                        <td align="right" style="font-size:13px; font-weight:700; padding:6px 0;">
                                            {{ $balanceAfter }} hari
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div style="margin-top:30px; text-align:center;">
                                <a href="{{ $approvalUrl }}" style="display:inline-block; background:linear-gradient(135deg,#7a1d35,#a32644); color:#ffffff; text-decoration:none; font-weight:700; padding:13px 28px; border-radius:999px; box-shadow:0 10px 22px rgba(122,29,53,0.25);">
                                    Buka Panel Approval
                                </a>
                            </div>

                            <p style="font-size:12px; line-height:1.6; color:#7c8794; margin:24px 0 0; text-align:center;">
                                Email ini dikirim otomatis oleh sistem. Jika tombol tidak bisa dibuka, login ke panel lalu masuk ke menu Pengajuan Cuti.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background:#2a1720; padding:22px 34px; text-align:center;">
                            <div style="font-size:13px; color:#f8d69a; font-weight:700;">
                                Hotel Leave Management
                            </div>
                            <div style="font-size:12px; color:#d8c8c0; margin-top:6px; line-height:1.6;">
                                Internal Hospitality Staff Leave System
                            </div>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>
</body>
</html>