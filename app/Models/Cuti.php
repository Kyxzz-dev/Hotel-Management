<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pegawai_id',
        'nama',
        'jenis_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_hari',
        'alasan',
        'status',

        'position',
        'department',
        'last_day_of_work',
        'first_day_of_work',
        'entitlement',
        'balance_before',
        'request_day',
        'balance_after',
        'person_in_charge',
        'remarks',
        'attachment',
        'approved_at',
        'rejected_at',
    ];

    public function pegawai()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getAttachmentUrlAttribute()
    {
        return $this->attachment ? asset('storage/' . $this->attachment) : null;
    }
}
