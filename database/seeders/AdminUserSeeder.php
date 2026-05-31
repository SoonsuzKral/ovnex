<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@ovnex.io'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Admin kullanıcı oluşturuldu: admin@ovnex.io / admin123');
    }
}
