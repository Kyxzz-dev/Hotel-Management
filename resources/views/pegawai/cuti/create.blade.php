@extends('layouts.pegawai')

@section('title', 'Ajukan Cuti')

@section('content')
@php
    $saldoCuti = isset($leaveSummary) ? (int) $leaveSummary['balance'] : 0;
    $hakCuti = isset($leaveSummary) ? (int) $leaveSummary['entitlement'] : 0;
    $tanggalMasuk = $leaveSummary['employment_start'] ?? null;
    $nextSlotDate = $leaveSummary['next_slot_date'] ?? null;
@endphp
<div class="page-header">
    <div>
        <div class="page-kicker">
            <i class="fa fa-calendar-plus"></i> Form Pengajuan
        </div>
        <h1 class="page-title">Ajukan Cuti Baru</h1>
        <p class="page-subtitle">
            Isi data pengajuan cuti sesuai dengan formulir manual. Pastikan tanggal dan alasan cuti sudah benar sebelum dikirim.
        </p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="panel-card">
            <div class="panel-card-header">
                <h5 class="fw-bold mb-1">Data Pengajuan Cuti</h5>
                <div class="text-muted small">
                    Data ini akan ditampilkan pada halaman pengajuan, approval, laporan, dan export.
                </div>
            </div>

            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <div class="fw-bold mb-1">
                            <i class="fa fa-triangle-exclamation me-1"></i> Periksa kembali input kamu:
                        </div>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('pegawai.cuti.store') }}" method="POST" id="leaveForm" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

                        {{-- Jenis Cuti --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jenis Cuti</label>
                            <select name="jenis_cuti"
                                    class="form-control @error('jenis_cuti') is-invalid @enderror"
                                    required>
                                <option value="">-- Pilih Jenis Cuti --</option>
                                <option value="Annual" {{ old('jenis_cuti') == 'Annual' ? 'selected' : '' }}>Annual Leave</option>
                                <option value="Sick" {{ old('jenis_cuti') == 'Sick' ? 'selected' : '' }}>Sick Leave</option>
                                <option value="Maternity" {{ old('jenis_cuti') == 'Maternity' ? 'selected' : '' }}>Maternity Leave</option>
                                <option value="Others" {{ old('jenis_cuti') == 'Others' ? 'selected' : '' }}>Other Leave</option>
                            </select>
                            @error('jenis_cuti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jumlah Hari --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jumlah Hari</label>
                            <input type="number"
                                   name="jumlah_hari"
                                   id="jumlah_hari"
                                   data-days-output
                                   class="form-control @error('jumlah_hari') is-invalid @enderror"
                                   value="{{ old('jumlah_hari') }}"
                                   min="1"
                                   readonly
                                   required>
                            @error('jumlah_hari')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Jumlah hari dihitung otomatis dari tanggal mulai dan selesai.
                            </div>
                        </div>

                        {{-- Tanggal Mulai --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Mulai Cuti</label>
                            <input type="date"
                                   name="tanggal_mulai"
                                   id="tanggal_mulai"
                                   class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                   value="{{ old('tanggal_mulai') }}"
                                   min="{{ date('Y-m-d') }}"
                                   required>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal Selesai --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Selesai Cuti</label>
                            <input type="date"
                                   name="tanggal_selesai"
                                   id="tanggal_selesai"
                                   class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                   value="{{ old('tanggal_selesai') }}"
                                   min="{{ date('Y-m-d') }}"
                                   required>
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Last Day of Work --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Last Day of Work</label>
                            <input type="date"
                                   name="last_day_of_work"
                                   class="form-control @error('last_day_of_work') is-invalid @enderror"
                                   value="{{ old('last_day_of_work') }}"
                                   required>
                            @error('last_day_of_work')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- First Day of Work --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">First Day of Work</label>
                            <input type="date"
                                   name="first_day_of_work"
                                   class="form-control @error('first_day_of_work') is-invalid @enderror"
                                   value="{{ old('first_day_of_work') }}"
                                   required>
                            @error('first_day_of_work')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Person In Charge --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Person In Charge During Absent</label>
                            <input type="text"
                                   name="person_in_charge"
                                   class="form-control @error('person_in_charge') is-invalid @enderror"
                                   value="{{ old('person_in_charge') }}"
                                   placeholder="Masukkan nama pengganti / penanggung jawab selama cuti"
                                   required>
                            @error('person_in_charge')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Alasan --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Reason for Request / Alasan Cuti</label>
                            <textarea name="alasan"
                                      id="alasan"
                                      rows="4"
                                      class="form-control @error('alasan') is-invalid @enderror"
                                      placeholder="Contoh: Berobat, urusan keluarga, keperluan penting, dll."
                                      required>{{ old('alasan') }}</textarea>
                            @error('alasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text"><span id="alasanCount">0</span> karakter</div>
                        </div>

                        {{-- Remarks --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Remarks</label>
                            <textarea name="remarks"
                                      rows="3"
                                      class="form-control @error('remarks') is-invalid @enderror"
                                      placeholder="Tambahkan keterangan tambahan jika ada">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Attachment (Wajib) --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Lampiran Surat <span class="text-danger">*</span></label>
                            <input type="file"
                                   name="attachment"
                                   class="form-control @error('attachment') is-invalid @enderror"
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                   required>
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-danger">
                                <i class="fa fa-asterisk me-1"></i>
                                Format: PDF, JPG, JPEG, PNG, DOC, DOCX (Max 2MB) - <strong>Wajib diisi</strong>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">
                        <a href="{{ route('pegawai.cuti.index') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-arrow-left me-1"></i> Kembali
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-paper-plane me-1"></i> Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">
        <div class="panel-card">
            <div class="card-body">
                <div class="action-icon mb-3" style="background:linear-gradient(135deg,#16a34a,#22c55e)">
                    <i class="fa fa-lightbulb"></i>
                </div>

                <h5 class="fw-bold">Preview Pengajuan</h5>
                <div class="quick-tip mt-3">
                    <div class="d-flex justify-content-between mb-2"><span>Durasi</span><strong id="previewDays">0 hari</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Mulai</span><strong id="previewStart">-</strong></div>
                    <div class="d-flex justify-content-between"><span>Selesai</span><strong id="previewEnd">-</strong></div>
                </div>

                <h5 class="fw-bold mt-4">Ketentuan Cuti</h5>

                <div class="quick-tip mt-3">
                    <div class="mb-2">
                        <i class="fa fa-check text-success me-1"></i>
                        Pastikan jenis cuti sesuai kebutuhan.
                    </div>
                    <div class="mb-2">
                        <i class="fa fa-check text-success me-1"></i>
                        Tanggal selesai tidak boleh lebih awal dari tanggal mulai.
                    </div>
                    <div class="mb-2">
                        <i class="fa fa-check text-success me-1"></i>
                        Jumlah hari dihitung otomatis oleh sistem.
                    </div>
                    <div>
                        <i class="fa fa-check text-success me-1"></i>
                        Pengajuan hanya bisa dikirim jika saldo cuti cukup.
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-card mt-4">
            <div class="card-body">
                <h6 class="fw-bold mb-2">
                    <i class="fa fa-circle-info me-1"></i> Informasi
                </h6>
                <div class="quick-tip">
                    <div class="d-flex justify-content-between mb-2"><span>Saldo saat ini</span><strong>{{ $saldoCuti }} hari</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Hak terkumpul</span><strong>{{ $hakCuti }} hari</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Tanggal masuk</span><strong>{{ $tanggalMasuk ? $tanggalMasuk->format('d M Y') : '-' }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Slot berikutnya</span><strong>{{ $nextSlotDate ? $nextSlotDate->format('d M Y') : '-' }}</strong></div>
                </div>
                <p class="text-muted small mt-3 mb-0">
                    Staff baru belum bisa cuti sebelum genap 1 bulan kerja. Setelah itu saldo bertambah 1 hari per bulan dan sisa saldo otomatis terbawa.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const mulai = document.getElementById('tanggal_mulai');
    const selesai = document.getElementById('tanggal_selesai');
    const jumlahHari = document.getElementById('jumlah_hari');

    function formatTanggal(value) {
        if (!value) return '-';
        return new Date(value).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function syncPreview(days = 0) {
        document.getElementById('previewDays').textContent = (days || 0) + ' hari';
        document.getElementById('previewStart').textContent = formatTanggal(mulai?.value);
        document.getElementById('previewEnd').textContent = formatTanggal(selesai?.value);
    }

    function hitungJumlahHari() {
        if (!mulai.value || !selesai.value) {
            jumlahHari.value = '';
            syncPreview(0);
            return;
        }

        const start = new Date(mulai.value);
        const end = new Date(selesai.value);

        if (end < start) {
            selesai.value = mulai.value;
        }

        const startDate = new Date(mulai.value);
        const endDate = new Date(selesai.value);

        const selisihWaktu = endDate.getTime() - startDate.getTime();
        const selisihHari = Math.floor(selisihWaktu / (1000 * 60 * 60 * 24)) + 1;

        jumlahHari.value = selisihHari > 0 ? selisihHari : 1;
        syncPreview(jumlahHari.value);
    }

    mulai?.addEventListener('change', function () {
        selesai.min = this.value;

        if (!selesai.value || selesai.value < this.value) {
            selesai.value = this.value;
        }

        hitungJumlahHari();
    });

    selesai?.addEventListener('change', function () {
        if (mulai.value && this.value < mulai.value) {
            this.value = mulai.value;
        }

        hitungJumlahHari();
    });

    document.addEventListener('DOMContentLoaded', function () {
        hitungJumlahHari();
        const alasan = document.getElementById('alasan');
        const count = document.getElementById('alasanCount');
        const updateCount = () => count.textContent = (alasan.value || '').length;
        alasan?.addEventListener('input', updateCount);
        updateCount();
    });
</script>
@endpush