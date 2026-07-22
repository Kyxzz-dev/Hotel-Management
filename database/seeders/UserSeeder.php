<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        foreach (User::DEPARTMENTS as $department) {
            Department::updateOrCreate(['name' => $department], ['is_active' => true]);
        }

        foreach (User::POSITIONS as $position) {
            Position::updateOrCreate(['name' => $position], ['is_active' => true]);
        }

        $users = [
            [
                'name' => 'HRD',
                'email' => 'hrd@cuti.com',
                'password' => Hash::make('hrd123'),
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'L',
                'role' => 'hrd',
                'position' => 'HRD',
                'department' => 'People & Culture',
            ],
            [
                'name' => 'Head Department',
                'email' => 'head@cuti.com',
                'password' => Hash::make('head123'),
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'L',
                'role' => 'head_department',
                'position' => 'Head Department',
                'department' => 'People & Culture',
            ],
            [
                'name' => 'GM',
                'email' => 'gm@cuti.com',
                'password' => Hash::make('gm123'),
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'L',
                'role' => 'gm',
                'position' => 'General Manager',
                'department' => 'People & Culture',
            ],
            [
                'name' => 'Staff Demo',
                'email' => 'staff@cuti.com',
                'password' => Hash::make('staff123'),
                'tanggal_lahir' => '1998-01-01',
                'jenis_kelamin' => 'P',
                'role' => 'staff',
                'position' => 'Staff',
                'department' => 'Front Office',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}
