<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat Akun Admin (Untuk Login Minggu Depan) 
        User::create([
            'identity_number' => '11111',
            'name' => 'Admin Utama',
            'email' => 'admin@sigap.com',
            'password' => Hash::make('password'), // Password dienkripsi 
            'role' => 'admin',
            'phone' => '08123456789'
        ]);
        // Membuat Akun Warga 
        User::create([
            'identity_number' => '32010001',
            'name' => 'Warga Test',
            'email' => 'warga@sigap.com',
            'password' => Hash::make('password'),
            'role' => 'masyarakat',
            'phone' => '08987654321'
        ]);
    }
}
