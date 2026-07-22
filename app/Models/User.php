<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLES = ['hrd', 'head_department', 'gm', 'staff'];

    public const DEPARTMENTS = [
        'Sales & Marketing',
        'Finance',
        'Front Office',
        'Food & Beverage Departement',
        'Housekeeping',
        'Engineering',
        'Wellness',
        'Security',
        'People & Culture',
    ];

    public const POSITIONS = [
        'HRD',
        'Head Department',
        'General Manager',
        'Staff',
    ];

    protected $fillable = [
        'name',
        'email',
        'tanggal_masuk',
        'password',
        'tanggal_lahir',
        'jenis_kelamin',
        'role',
        'position',
        'department',
        'jabatan',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'tanggal_masuk' => 'date',
        'tanggal_lahir' => 'date',
    ];


    public static function departmentOptions(): array
    {
        try {
            if (Schema::hasTable('departments')) {
                $items = Department::where('is_active', true)->orderBy('name')->pluck('name')->all();
                if (! empty($items)) {
                    return $items;
                }
            }
        } catch (\Throwable $exception) {
            // Fallback digunakan ketika migration belum dijalankan.
        }

        return self::DEPARTMENTS;
    }

    public static function positionOptions(): array
    {
        try {
            if (Schema::hasTable('positions')) {
                $items = Position::where('is_active', true)->orderBy('name')->pluck('name')->all();
                if (! empty($items)) {
                    return $items;
                }
            }
        } catch (\Throwable $exception) {
            // Fallback digunakan ketika migration belum dijalankan.
        }

        return self::POSITIONS;
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'hrd' => 'HRD',
            'head_department' => 'Head Department',
            'gm' => 'General Manager',
            'staff' => 'Staff',
            default => ucfirst((string) $this->role),
        };
    }

    public function getPositionLabelAttribute(): string
    {
        return $this->position ?: $this->role_label;
    }


    public function getJabatanLabelAttribute(): string
    {
        return $this->jabatan ?: 'Jabatan belum diisi';
    }

    public function isApprover(): bool
    {
        return in_array($this->role, ['head_department', 'gm'], true);
    }

    public function isManagement(): bool
    {
        return in_array($this->role, ['hrd', 'head_department', 'gm'], true);
    }

    public function canManageStaff(): bool
    {
        return $this->role === 'hrd';
    }

    public function canManageAccounts(): bool
    {
        return $this->role === 'hrd';
    }

    public function cutis()
    {
        return $this->hasMany(Cuti::class);
    }
}
