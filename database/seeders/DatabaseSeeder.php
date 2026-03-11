<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'مدير النظام',
            'username' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $this->call(
            GeneralSettingSeeder::class,
        );

    }
}
