@php
    $statusText = [
        'pending' => 'Menunggu',
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
    ][$status] ?? ucfirst($status ?? '-');
    $statusIcon = [
        'pending' => 'fa-clock',
        'disetujui' => 'fa-check',
        'ditolak' => 'fa-xmark',
    ][$status] ?? 'fa-circle';
@endphp
<span class="badge-status badge-{{ $status }}">
    <i class="fa {{ $statusIcon }}"></i> {{ $statusText }}
</span>
