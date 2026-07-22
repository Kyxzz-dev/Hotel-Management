<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Cuti Staff Tahun {{ $tahun }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000000;
            padding: 6px;
            vertical-align: middle;
        }

        th {
            background-color: #d9eaf7;
            font-weight: bold;
            text-align: center;
        }

        .title {
            background-color: #1f4e79;
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }

        .subtitle {
            background-color: #d9eaf7;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .success {
            background-color: #d9ead3;
        }

        .warning {
            background-color: #fff2cc;
        }

        .danger {
            background-color: #f4cccc;
        }

        .info {
            background-color: #cfe2f3;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="22" class="title">
                    LAPORAN DETAIL CUTI STAFF TAHUN {{ $tahun }}
                </th>
            </tr>

            <tr>
                <th colspan="22" class="subtitle">
                    Batas Cuti Per Staff Per Tahun: {{ $batas }} Hari
                </th>
            </tr>

            <tr>
                <th>No</th>
                <th>Nama Staff</th>
                <th>Email</th>
                <th>Position</th>
                <th>Department</th>
                <th>Jenis Cuti</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Jumlah Hari</th>
                <th>Last Day of Work</th>
                <th>First Day of Work</th>
                <th>Entitlement</th>
                <th>Balance Before</th>
                <th>Request</th>
                <th>Balance After</th>
                <th>Reason</th>
                <th>Person In Charge</th>
                <th>Remarks</th>
                <th>Status</th>
                <th>Tanggal Pengajuan</th>
                <th>Approved At</th>
                <th>Rejected At</th>
            </tr>
        </thead>

        <tbody>
            @php $no = 1; @endphp

            @foreach($pegawais as $pegawai)
                @foreach($pegawai->cutis as $cuti)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td class="text-left">{{ $pegawai->name }}</td>
                        <td class="text-left">{{ $pegawai->email }}</td>
                        <td class="text-left">{{ $cuti->position ?: ($pegawai->position_label ?? '-') }}</td>
                        <td class="text-left">{{ $cuti->department ?: ($pegawai->department ?? '-') }}</td>
                        <td class="text-center">{{ $cuti->jenis_cuti ?? '-' }}</td>

                        <td class="text-center">
                            {{ $cuti->tanggal_mulai ? \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d-m-Y') : '-' }}
                        </td>

                        <td class="text-center">
                            {{ $cuti->tanggal_selesai ? \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d-m-Y') : '-' }}
                        </td>

                        <td class="text-center">
                            {{ $cuti->jumlah_hari ?? $cuti->request_day ?? 0 }}
                        </td>

                        <td class="text-center">
                            {{ $cuti->last_day_of_work ? \Carbon\Carbon::parse($cuti->last_day_of_work)->format('d-m-Y') : '-' }}
                        </td>

                        <td class="text-center">
                            {{ $cuti->first_day_of_work ? \Carbon\Carbon::parse($cuti->first_day_of_work)->format('d-m-Y') : '-' }}
                        </td>

                        <td class="text-center info">
                            {{ $cuti->entitlement ?? $batas }}
                        </td>

                        <td class="text-center warning">
                            {{ $cuti->balance_before ?? 0 }}
                        </td>

                        <td class="text-center">
                            {{ $cuti->request_day ?? $cuti->jumlah_hari ?? 0 }}
                        </td>

                        <td class="text-center success">
                            {{ $cuti->balance_after ?? 0 }}
                        </td>

                        <td class="text-left">
                            {{ $cuti->alasan ?? '-' }}
                        </td>

                        <td class="text-left">
                            {{ $cuti->person_in_charge ?? '-' }}
                        </td>

                        <td class="text-left">
                            {{ $cuti->remarks ?? '-' }}
                        </td>

                        <td class="text-center">
                            {{ $cuti->status ?? '-' }}
                        </td>

                        <td class="text-center">
                            {{ $cuti->created_at ? \Carbon\Carbon::parse($cuti->created_at)->format('d-m-Y H:i') : '-' }}
                        </td>

                        <td class="text-center">
                            {{ $cuti->approved_at ? \Carbon\Carbon::parse($cuti->approved_at)->format('d-m-Y H:i') : '-' }}
                        </td>

                        <td class="text-center">
                            {{ $cuti->rejected_at ? \Carbon\Carbon::parse($cuti->rejected_at)->format('d-m-Y H:i') : '-' }}
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th colspan="4" class="subtitle">
                    Keterangan
                </th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td><strong>Entitlement</strong></td>
                <td colspan="3">Jumlah hak cuti tahunan staff.</td>
            </tr>

            <tr>
                <td><strong>Balance Before</strong></td>
                <td colspan="3">Sisa cuti sebelum pengajuan dibuat.</td>
            </tr>

            <tr>
                <td><strong>Request</strong></td>
                <td colspan="3">Jumlah hari cuti yang diajukan.</td>
            </tr>

            <tr>
                <td><strong>Balance After</strong></td>
                <td colspan="3">Sisa cuti setelah pengajuan. Jika ditolak, balance kembali seperti sebelum pengajuan.</td>
            </tr>

            <tr>
                <td><strong>Approved At</strong></td>
                <td colspan="3">Waktu pengajuan cuti disetujui.</td>
            </tr>

            <tr>
                <td><strong>Rejected At</strong></td>
                <td colspan="3">Waktu pengajuan cuti ditolak.</td>
            </tr>
        </tbody>
    </table>
</body>
</html>