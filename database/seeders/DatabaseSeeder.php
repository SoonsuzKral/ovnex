<?php
/*
 * OVNEX — Veritabanı tohumlayıcı
 * Geliştirme ve test için demo verileri oluşturur
 */
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
