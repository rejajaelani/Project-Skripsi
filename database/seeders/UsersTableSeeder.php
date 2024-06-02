<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Buat data dummy pengguna
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'nama_lengkap' => 'Admin',
            'email' => 'admin@example.com',
            'hak_akses' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ])->createToken('personal-token');
    }
}
